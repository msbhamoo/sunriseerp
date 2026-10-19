<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * BatchMigrateControllers
 *
 * Scans application/controllers and generates clean CodeIgniter 4
 * controllers in app/Controllers, matching routes and parameters.
 */
class BatchMigrateControllers extends BaseCommand
{
    protected $group       = 'Migration';
    protected $name        = 'migrate:batch-controllers';
    protected $description = 'Automatically generate CI4 controllers from CI3 controllers';
    protected $usage       = 'migrate:batch-controllers [directory]';

    public function run(array $params)
    {
        $targetDir = $params[0] ?? 'admin';
        $ci3BasePath = APPPATH . '../application/controllers';
        $fullPath = realpath($ci3BasePath . '/' . $targetDir);

        if (!$fullPath || !is_dir($fullPath)) {
            CLI::error("Directory not found: " . $ci3BasePath . '/' . $targetDir);
            return;
        }

        CLI::write("Scanning CI3 controllers in: {$fullPath}", 'green');

        $files = glob($fullPath . '/*.php');
        $migratedCount = 0;
        $skippedCount = 0;

        foreach ($files as $file) {
            $filename = basename($file);
            $className = basename($file, '.php');

            // Skip controllers already migrated manually
            $alreadyMigrated = [
                'Classes.php', 'Sections.php', 'Stuattendence.php',
                'Feegroup.php', 'Feetype.php', 'Feemaster.php',
                'Studentcall.php', 'Ptm.php'
            ];

            if (in_array($filename, $alreadyMigrated, true)) {
                CLI::write("  [SKIPPED - Already Migrated] {$filename}", 'yellow');
                $skippedCount++;
                continue;
            }

            CLI::write("  -> Processing {$filename}...");
            $migrated = $this->migrateController($file, $targetDir);
            if ($migrated) {
                $migratedCount++;
            } else {
                $skippedCount++;
            }
        }

        CLI::newLine();
        CLI::write("Migration completed! Migrated: {$migratedCount}, Skipped: {$skippedCount}", 'green');
    }

    protected function migrateController(string $filePath, string $subfolder): bool
    {
        $content = file_get_contents($filePath);
        $className = basename($filePath, '.php');

        // Extract methods
        preg_match_all('/public\s+function\s+([a-zA-Z0-9_]+)\s*\((.*?)\)/i', $content, $matches, PREG_SET_ORDER);
        if (empty($matches)) {
            return false;
        }

        $namespace = 'App\\Controllers\\' . ucfirst($subfolder);
        $ci4ControllerName = ucfirst($className) . 'Controller';
        $targetDir = APPPATH . 'Controllers/' . ucfirst($subfolder);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetFile = $targetDir . '/' . $ci4ControllerName . '.php';

        $methodStubs = [];
        foreach ($matches as $match) {
            $methodName = $match[1];
            $params = $match[2];

            if ($methodName === '__construct') {
                continue;
            }

            // Clean method parameter names for signature
            $methodStubs[] = "    public function {$methodName}({$params})\n" .
                             "    {\n" .
                             "        return \$this->dispatchLegacy('{$className}', '{$methodName}', func_get_args());\n" .
                             "    }";
        }

        $methodsCode = implode("\n\n", $methodStubs);

        $code = <<<PHP
<?php

namespace {$namespace};

use App\Controllers\BaseController;

/**
 * {$ci4ControllerName}
 *
 * Auto-generated CI4 controller migrating CI3 {$className}.
 */
class {$ci4ControllerName} extends BaseController
{
{$methodsCode}

    protected function dispatchLegacy(string \$controller, string \$method, array \$params)
    {
        // Set up compatibility environment
        \$_SERVER['CI4_LEGACY_CONTROLLER'] = \$controller;
        \$_SERVER['CI4_LEGACY_METHOD'] = \$method;
        \$_SERVER['CI4_LEGACY_PARAMS'] = \$params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        \$target = APPPATH . '../application/controllers/{$subfolder}/' . \$controller . '.php';
        if (file_exists(\$target)) {
            require_once \$target;
            \$instance = new \$controller();
            return call_user_func_array([\$instance, \$method], \$params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}
PHP;

        file_put_contents($targetFile, $code);
        return true;
    }
}
