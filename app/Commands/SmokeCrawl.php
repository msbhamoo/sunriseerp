<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SmokeCrawl extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'smoke:crawl';
    protected $description = 'Crawls all application routes across user roles and groups failures by error';

    protected $config;
    protected $skipKeywords = [];
    protected $cookieFiles = [];
    protected $results = [];
    protected $errorGroups = [];

    public function run(array $params)
    {
        $targetRole   = $params[0] ?? (CLI::getOption('role') ?: 'all');
        $limit        = (int)($params[1] ?? (CLI::getOption('limit') ?: 0));
        $offset       = (int)($params[2] ?? (CLI::getOption('offset') ?: 0));
        $failuresOnly = CLI::getOption('failures-only') || in_array('--failures-only', $params, true) || in_array('-f', $params, true);
        $this->config = config('SmokeTest');
        if (!$this->config) {
            CLI::error('Configuration App\Config\SmokeTest not found!');
            return;
        }

        $this->skipKeywords = array_map('strtolower', $this->config->skipKeywords ?? [
            'delete', 'remove', 'destroy', 'drop', 'trash',
            'send', 'sync', 'pay', 'payment', 'payout',
            'logout', 'truncate', 'backup', 'restore', 'reset',
            'export', 'download'
        ]);

        $baseUrl = rtrim($this->config->baseUrl, '/') . '/';
        CLI::write("=== Automated Smoke Crawler ===", 'yellow');
        CLI::write("Base URL: " . $baseUrl, 'cyan');
        CLI::write("Target Mode: " . strtoupper($targetRole) . ($limit > 0 ? " (Batch: limit {$limit}, offset {$offset})" : ""), 'magenta');

        // 1. Gather all URLs to crawl
        CLI::write("Gathering routes and endpoints...", 'white');
        $routesByRole = $this->collectRoutes();
        CLI::write("Total Admin Routes: " . count($routesByRole['admin']), 'green');
        CLI::write("Total Student Routes: " . count($routesByRole['student']), 'green');
        CLI::write("Total Parent Routes: " . count($routesByRole['parent']), 'green');
        CLI::write("Total Public Routes: " . count($routesByRole['public']), 'green');

        // 2. Select roles to crawl based on targetRole
        $rolesToCrawl = [];
        if ($targetRole === 'public') {
            $rolesToCrawl = ['public'];
        } elseif ($targetRole === 'admin') {
            if ($this->loginUser('admin', $this->config->admin)) {
                $rolesToCrawl = ['admin'];
            }
        } elseif ($targetRole === 'student') {
            if ($this->loginUser('student', $this->config->student)) {
                $rolesToCrawl = ['student'];
            }
        } elseif ($targetRole === 'parent') {
            if ($this->loginUser('parent', $this->config->parent)) {
                $rolesToCrawl = ['parent'];
            }
        } else {
            // all
            $rolesToCrawl = ['public'];
            if (!empty($this->config->admin['username']) && $this->loginUser('admin', $this->config->admin)) {
                $rolesToCrawl[] = 'admin';
            }
            if (!empty($this->config->student['username']) && $this->loginUser('student', $this->config->student)) {
                $rolesToCrawl[] = 'student';
            }
            if (!empty($this->config->parent['username']) && $this->loginUser('parent', $this->config->parent)) {
                $rolesToCrawl[] = 'parent';
            }
        }

        // 3. Crawl routes per role
        $totalCrawled = 0;
        $totalFailures = 0;

        if ($failuresOnly) {
            $reportFiles = glob(WRITEPATH . 'smoke_reports/crawl_report_*.json');
            usort($reportFiles, fn($a, $b) => filemtime($b) <=> filemtime($a));
            $failingUrls = [];
            if (!empty($reportFiles)) {
                $lastRep = json_decode(file_get_contents($reportFiles[0]), true);
                if (!empty($lastRep['error_groups'])) {
                    foreach ($lastRep['error_groups'] as $grp) {
                        foreach ($grp['urls'] as $u) {
                            $uriStr = is_array($u) ? ($u['uri'] ?? '') : $u;
                            if ($uriStr) $failingUrls[ltrim($uriStr, '/')] = true;
                        }
                    }
                }
            }
            CLI::write(" [MODE] Failures-only active: testing only " . count($failingUrls) . " URLs from previous crawl report.", "yellow");
        }

        foreach ($rolesToCrawl as $role) {
            $urls = $routesByRole[$role] ?? [];
            if ($failuresOnly) {
                $urls = array_values(array_filter($urls, fn($uri) => isset($failingUrls[ltrim($uri, '/')])));
            }
            if (empty($urls)) {
                continue;
            }

            if ($limit > 0) {
                $urls = array_slice($urls, $offset, $limit);
            }

            CLI::newLine();
            CLI::write("Crawling {$role} routes (" . count($urls) . " URLs" . ($limit > 0 ? ", offset {$offset}" : "") . ")...", 'yellow');

            $i = 0;
            $count = count($urls);
            foreach ($urls as $uri) {
                $i++;
                $fullUrl = $baseUrl . ltrim($uri, '/');

                $status = $this->requestPage($role, $fullUrl, $uri);
                $totalCrawled++;

                if ($status['is_error']) {
                    $totalFailures++;
                    CLI::print("F", 'red');
                } else {
                    CLI::print(".", 'green');
                }

                if ($i % 50 === 0 || $i === $count) {
                    CLI::write(" [{$i}/{$count}]");
                }
            }
        }

        // 4. Output Summary & Error Grouping
        CLI::newLine();
        $this->generateReport($totalCrawled, $totalFailures);

        // 5. Cleanup temp cookies
        foreach ($this->cookieFiles as $cFile) {
            @unlink($cFile);
        }
    }

    /**
     * Parse controllers and wrappers to discover non-parameterized GET safe endpoints.
     */
    protected function collectRoutes(): array
    {
        $routes = [
            'admin'   => [],
            'student' => [],
            'parent'  => [],
            'public'  => [],
        ];

        // Map controller directories
        $modules = [
            'Admin'           => 'admin',
            'Accounts'        => 'admin',
            'Cbseexam'        => 'admin',
            'Onlineadmission' => 'public',
            'User'            => 'student', // student / parent endpoints
        ];

        foreach ($modules as $folder => $role) {
            $dir = APPPATH . 'Controllers/' . $folder;
            if (!is_dir($dir)) continue;

            $files = glob($dir . '/*Controller.php');
            foreach ($files as $file) {
                $ctrlName = basename($file, '.php');
                $slug = strtolower(str_replace('Controller', '', $ctrlName));

                $methods = $this->getPublicMethods($file);
                foreach ($methods as $method) {
                    if ($this->isSkipped($method['name'])) {
                        continue;
                    }
                    if ($method['required_params'] > 0) {
                        // Skip routes requiring mandatory arguments for initial sweep
                        continue;
                    }

                    $prefix = strtolower($folder);
                    $methodSlug = ($method['name'] === 'index') ? '' : '/' . $method['name'];
                    $uri = "{$prefix}/{$slug}{$methodSlug}";
                    $routes[$role][] = $uri;

                    if ($role === 'student') {
                        // Also test under parent session
                        $routes['parent'][] = $uri;
                    }
                }
            }
        }

        // Root Controllers
        $rootFiles = glob(APPPATH . 'Controllers/*Controller.php');
        foreach ($rootFiles as $file) {
            $ctrlName = basename($file, '.php');
            $slug = strtolower(str_replace('Controller', '', $ctrlName));
            if (in_array($slug, ['home', 'base'], true)) continue;

            $methods = $this->getPublicMethods($file);
            foreach ($methods as $method) {
                if ($this->isSkipped($method['name'])) {
                    continue;
                }
                if ($method['required_params'] > 0) {
                    continue;
                }

                $methodSlug = ($method['name'] === 'index') ? '' : '/' . $method['name'];
                $uri = "{$slug}{$methodSlug}";
                $routes['admin'][] = $uri;
            }
        }

        // Deduplicate
        foreach ($routes as $k => $list) {
            $routes[$k] = array_values(array_unique($list));
        }

        return $routes;
    }

    /**
     * Inspect public methods and argument counts via Reflection.
     */
    protected function getPublicMethods(string $file): array
    {
        $content = file_get_contents($file);
        if (!preg_match('/namespace\s+([^;]+);/', $content, $nsMatch)) {
            return [];
        }
        $namespace = trim($nsMatch[1]);
        $class = basename($file, '.php');
        $fqcn = $namespace . '\\' . $class;

        if (!class_exists($fqcn, false)) {
            @include_once $file;
        }

        if (!class_exists($fqcn, false)) {
            return [];
        }

        $methods = [];
        try {
            $ref = new \ReflectionClass($fqcn);
            foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
                // Ignore inherited BaseController methods
                if ($m->class !== $fqcn) continue;
                if (str_starts_with($m->name, '__')) continue;

                $reqCount = $m->getNumberOfRequiredParameters();
                $methods[] = [
                    'name' => $m->name,
                    'required_params' => $reqCount,
                ];
            }
        } catch (\Throwable $e) {
            // Skip reflection errors
        }

        return $methods;
    }

    /**
     * Safety check on URI or method name.
     */
    protected function isSkipped(string $name): bool
    {
        $lower = strtolower($name);
        foreach ($this->skipKeywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                return true;
            }
        }
        return false;
    }

    protected array $sessionCookies = [];

    /**
     * Authenticates and captures session cookie.
     */
    protected function loginUser(string $role, array $cred): bool
    {
        $baseUrl = rtrim($this->config->baseUrl, '/') . '/';
        $loginUrl = $baseUrl . ltrim($cred['loginUrl'], '/');

        CLI::write("Authenticating {$role} at {$loginUrl}...", 'cyan');

        $headers = [];
        $ch = curl_init($loginUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'username' => $cred['username'],
                'password' => $cred['password'],
            ]),
            CURLOPT_HEADERFUNCTION => function($curl, $header) use (&$headers) {
                $len = strlen($header);
                $parts = explode(':', $header, 2);
                if (count($parts) === 2) {
                    $headers[strtolower(trim($parts[0]))][] = trim($parts[1]);
                }
                return $len;
            },
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT        => 15,
        ]);

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $cookieHeader = null;
        if (!empty($headers['set-cookie'])) {
            foreach ($headers['set-cookie'] as $cookieStr) {
                if (str_contains($cookieStr, 'ci_session=')) {
                    $cookieHeader = explode(';', $cookieStr)[0];
                    break;
                }
            }
        }

        if ($code === 302 && $cookieHeader) {
            $this->sessionCookies[$role] = $cookieHeader;
            CLI::write(" [OK] Logged in as {$role} successfully ({$cookieHeader}).", 'green');

            // For student and parent, ensure choose/dashboard session setup executes
            if (in_array($role, ['student', 'parent'], true)) {
                $chooseUrl = $baseUrl . 'user/user/choose';
                $chChoose = curl_init($chooseUrl);
                curl_setopt_array($chChoose, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_COOKIE         => $cookieHeader,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT        => 10,
                ]);
                curl_exec($chChoose);
                curl_close($chChoose);
            }

            return true;
        }

        CLI::error(" [FAIL] Could not log in as {$role} (HTTP {$code}). Skipping authenticated crawl for {$role}.");
        return false;
    }

    /**
     * Send GET request and check for errors / Whoops / 500s.
     */
    protected function requestPage(string $role, string $url, string $uri): array
    {
        $cookieHeader = $this->sessionCookies[$role] ?? null;

        $ch = curl_init($url);
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_USERAGENT      => 'SmokeCrawler/1.0',
        ];

        if ($cookieHeader) {
            $opts[CURLOPT_COOKIE] = $cookieHeader;
        }

        curl_setopt_array($ch, $opts);
        $startLogPos = $this->getLogFileSize();

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        $isError = false;
        $errorMsg = null;

        if ($code >= 500) {
            $isError = true;
        } elseif (str_contains($body, 'Whoops! We seem to have encountered a problem') ||
                  str_contains($body, 'An uncaught Exception was encountered') ||
                  str_contains($body, 'Fatal error:')) {
            $isError = true;
        }

        if ($isError) {
            $errorMsg = $this->extractRecentCriticalError($startLogPos, $body);
            $normalizedKey = $this->normalizeErrorMessage($errorMsg);

            if (!isset($this->errorGroups[$normalizedKey])) {
                $this->errorGroups[$normalizedKey] = [
                    'message' => $errorMsg,
                    'urls' => [],
                ];
            }
            $this->errorGroups[$normalizedKey]['urls'][] = [
                'role' => $role,
                'uri'  => $uri,
                'url'  => $url,
                'code' => $code,
            ];
        }

        return [
            'is_error' => $isError,
            'code'     => $code,
            'error'    => $errorMsg,
        ];
    }

    protected function getLogFileSize(): int
    {
        $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.log';
        return file_exists($logFile) ? filesize($logFile) : 0;
    }

    protected function extractRecentCriticalError(int $startPos, string $htmlBody): string
    {
        $logFile = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.log';
        if (file_exists($logFile)) {
            clearstatcache(true, $logFile);
            $currSize = filesize($logFile);
            if ($currSize > $startPos) {
                $fp = fopen($logFile, 'r');
                fseek($fp, $startPos);
                $diff = fread($fp, $currSize - $startPos);
                fclose($fp);

                if (preg_match('/CRITICAL\s+-\s+\d{4}-\d{2}-\d{2}[^>]+-->\s+(.+?)(?:\[Method:|\sin\s+[A-Z]:)/s', $diff, $m)) {
                    return trim($m[1]);
                }
            }
        }

        // Fallback: extract title or headline from Whoops HTML
        if (preg_match('/<header class="header">.*?<h1>(.*?)<\/h1>/si', $htmlBody, $m)) {
            return strip_tags(trim($m[1]));
        }
        if (preg_match('/<title>(.*?)<\/title>/si', $htmlBody, $m)) {
            return strip_tags(trim($m[1]));
        }

        return "HTTP 500 / Unknown Error";
    }

    protected function normalizeErrorMessage(string $msg): string
    {
        // Strip out specific variable instances / line numbers
        $normalized = preg_replace('/in\s+.*?\s+on\s+line\s+\d+/', '', $msg);
        $normalized = preg_replace('/#\d+\s+.*$/m', '', $normalized);
        $normalized = trim(preg_replace('/\s+/', ' ', $normalized));
        return substr($normalized, 0, 120);
    }

    protected function generateReport(int $totalCrawled, int $totalFailures): void
    {
        CLI::write("==================================================", 'yellow');
        CLI::write("SMOKE CRAWL SUMMARY", 'yellow');
        CLI::write("Total Pages Crawled: {$totalCrawled}");
        CLI::write("Total Failures:      {$totalFailures}", $totalFailures > 0 ? 'red' : 'green');
        CLI::write("Error Groups:        " . count($this->errorGroups));
        CLI::write("==================================================", 'yellow');

        if (empty($this->errorGroups)) {
            CLI::write("All crawled endpoints loaded cleanly with no 500 errors or Whoops pages!", 'green');
            return;
        }

        // Sort error groups by occurrence count descending
        uasort($this->errorGroups, fn($a, $b) => count($b['urls']) <=> count($a['urls']));

        $reportDir = WRITEPATH . 'smoke_reports';
        if (!is_dir($reportDir)) {
            @mkdir($reportDir, 0777, true);
        }

        $reportFile = $reportDir . '/crawl_report_' . date('Ymd_His') . '.json';
        file_put_contents($reportFile, json_encode([
            'crawled_at'     => date('c'),
            'total_crawled'  => $totalCrawled,
            'total_failures' => $totalFailures,
            'error_groups'   => $this->errorGroups,
        ], JSON_PRETTY_PRINT));

        CLI::newLine();
        CLI::write("Top Error Groups:", 'cyan');
        $rank = 1;
        foreach ($this->errorGroups as $group) {
            $count = count($group['urls']);
            CLI::write(" [{$rank}] ({$count} URLs) " . $group['message'], 'light_red');
            $sampleUrls = array_slice(array_column($group['urls'], 'uri'), 0, 3);
            CLI::write("     e.g.: " . implode(', ', $sampleUrls), 'dark_gray');
            $rank++;
        }

        CLI::newLine();
        CLI::write("Detailed JSON report saved to: " . $reportFile, 'green');
    }
}
