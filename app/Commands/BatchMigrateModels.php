<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * BatchMigrateModels
 *
 * Scans application/models and generates native PSR-4 CodeIgniter 4
 * models in app/Models/, extending App\Models\BaseModel.
 */
class BatchMigrateModels extends BaseCommand
{
    protected $group       = 'Migration';
    protected $name        = 'migrate:batch-models';
    protected $description = 'Automatically generate CI4 PSR-4 models from CI3 legacy models';
    protected $usage       = 'migrate:batch-models [subfolder]';

    public function run(array $params)
    {
        $subfolder = $params[0] ?? '';
        $isRoot = empty($subfolder) || $subfolder === '.' || $subfolder === '/';
        $sourcePath = $isRoot 
            ? realpath(APPPATH . '../application/models')
            : realpath(APPPATH . '../application/models/' . $subfolder);

        if (!$sourcePath || !is_dir($sourcePath)) {
            CLI::error("Source directory not found: " . ($isRoot ? 'application/models' : 'application/models/' . $subfolder));
            return;
        }

        $targetDir = $isRoot 
            ? APPPATH . 'Models'
            : APPPATH . 'Models/' . ucfirst($subfolder);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $files = glob($sourcePath . '/*.php');
        $migratedCount = 0;
        $skippedCount = 0;

        CLI::write("Scanning models in: {$sourcePath}", 'green');

        // Existing native models to skip overwriting
        $preserveList = [
            'BaseModel.php', 'ClassModel.php', 'SectionModel.php', 'ClasssectionModel.php',
            'StuattendenceModel.php', 'FeegroupModel.php', 'FeetypeModel.php', 'FeemasterModel.php',
            'FeesessiongroupModel.php', 'StudentcallModel.php', 'StudentfeemasterModel.php',
            'StudentModel.php', 'PtmModel.php', 'NotificationModel.php', 'SettingModel.php',
            'SessionModel.php', 'StaffModel.php', 'AttendencetypeModel.php'
        ];

        foreach ($files as $file) {
            $rawFilename = basename($file);
            $rawClassName = basename($file, '.php');

            // Convert Class_name_model -> ClassNameModel or Student_model -> StudentModel
            $ci4ClassName = $this->formatClassName($rawClassName);
            $targetFile = $targetDir . '/' . $ci4ClassName . '.php';

            if (in_array($ci4ClassName . '.php', $preserveList, true) && file_exists($targetFile)) {
                CLI::write("  [PRESERVED Native CI4 Model] {$ci4ClassName}.php", 'yellow');
                $skippedCount++;
                continue;
            }

            CLI::write("  -> Generating {$ci4ClassName} (from {$rawFilename})...");
            $success = $this->migrateModel($file, $rawClassName, $ci4ClassName, $isRoot ? '' : ucfirst($subfolder));
            if ($success) {
                $migratedCount++;
            } else {
                $skippedCount++;
            }
        }

        CLI::newLine();
        CLI::write("Models batch migration complete! Migrated: {$migratedCount}, Skipped/Preserved: {$skippedCount}", 'green');
    }

    protected function formatClassName(string $name): string
    {
        // If already PascalCase with Model suffix (e.g., StudentModel)
        if (str_ends_with(strtolower($name), '_model')) {
            $base = substr($name, 0, -6);
            $parts = explode('_', $base);
            $camel = implode('', array_map('ucfirst', $parts));
            return $camel . 'Model';
        }

        if (str_ends_with(strtolower($name), 'model')) {
            return ucfirst($name);
        }

        $parts = explode('_', $name);
        return implode('', array_map('ucfirst', $parts)) . 'Model';
    }

    protected function migrateModel(string $sourceFile, string $legacyClass, string $ci4Class, string $subNamespace): bool
    {
        $content = file_get_contents($sourceFile);
        $namespace = empty($subNamespace) ? 'App\\Models' : 'App\\Models\\' . $subNamespace;
        $targetDir = empty($subNamespace) ? APPPATH . 'Models' : APPPATH . 'Models/' . $subNamespace;
        $targetFile = $targetDir . '/' . $ci4Class . '.php';

        // Extract public methods and their signatures using token_get_all to handle multiline / complex params
        $methodStubs = [];
        $tokens = token_get_all($content);
        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION) {
                // Check if previous non-whitespace token was private or protected
                $isPublic = true;
                for ($p = $i - 1; $p >= 0; $p--) {
                    if (is_array($tokens[$p])) {
                        if ($tokens[$p][0] === T_PRIVATE || $tokens[$p][0] === T_PROTECTED) {
                            $isPublic = false;
                            break;
                        }
                        if ($tokens[$p][0] === T_PUBLIC || $tokens[$p][0] === T_STATIC) {
                            break;
                        }
                    }
                }

                if (!$isPublic) {
                    continue;
                }

                // Get function name
                $funcName = '';
                $n = $i + 1;
                while ($n < $count && is_array($tokens[$n]) && $tokens[$n][0] === T_WHITESPACE) {
                    $n++;
                }
                if ($n < $count && is_array($tokens[$n]) && $tokens[$n][0] === T_STRING) {
                    $funcName = $tokens[$n][1];
                }

                if (empty($funcName) || $funcName === '__construct') {
                    continue;
                }

                // Find '(' and matching ')'
                $openParen = $n + 1;
                while ($openParen < $count && $tokens[$openParen] !== '(') {
                    $openParen++;
                }

                if ($openParen >= $count) {
                    continue;
                }

                $parenDepth = 1;
                $paramTokens = [];
                for ($k = $openParen + 1; $k < $count; $k++) {
                    if ($tokens[$k] === '(') {
                        $parenDepth++;
                    } elseif ($tokens[$k] === ')') {
                        $parenDepth--;
                        if ($parenDepth === 0) {
                            break;
                        }
                    }
                    $paramTokens[] = is_array($tokens[$k]) ? $tokens[$k][1] : $tokens[$k];
                }

                $params = trim(implode('', $paramTokens));

                $methodStubs[] = "    public function {$funcName}({$params})\n" .
                                 "    {\n" .
                                 "        return \$this->callLegacyModel('{$legacyClass}', '{$funcName}', func_get_args());\n" .
                                 "    }";
            }
        }

        // Guess primary table name from file name
        $tableName = strtolower(str_replace(['_model', 'model'], '', $legacyClass));

        $methodsCode = implode("\n\n", $methodStubs);

        $code = <<<PHP
<?php

namespace {$namespace};

use App\Models\BaseModel;

/**
 * {$ci4Class}
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy {$legacyClass}.
 */
class {$ci4Class} extends BaseModel
{
    protected \$table = '{$tableName}';
    protected \$primaryKey = 'id';
    protected \$useAutoIncrement = true;
    protected \$returnType = 'array';

{$methodsCode}

    /**
     * Compatibility Bridge: Delegates execution to the legacy model instance
     * maintaining full compatibility with query builder and CI3 environment.
     */
    protected function callLegacyModel(string \$modelClass, string \$method, array \$args)
    {
        static \$instances = [];

        if (!isset(\$instances[\$modelClass])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            \$file = APPPATH . '../application/models/' . (\$this->subPath() ? \$this->subPath() . '/' : '') . \$modelClass . '.php';
            if (file_exists(\$file)) {
                require_once \$file;
                \$instances[\$modelClass] = new \$modelClass();
            } else {
                return null;
            }
        }

        return call_user_func_array([\$instances[\$modelClass], \$method], \$args);
    }

    protected function subPath(): string
    {
        return '{$subNamespace}';
    }
}
PHP;

        file_put_contents($targetFile, $code);
        return true;
    }
}
