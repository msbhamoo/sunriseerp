<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * GenerateModuleRoutes
 *
 * Scans app/Controllers and appends explicit route definitions
 * into app/Config/Routes.php, eliminating any dynamic controller lookup errors.
 */
class GenerateModuleRoutes extends BaseCommand
{
    protected $group       = 'Migration';
    protected $name        = 'migrate:generate-routes';
    protected $description = 'Generate explicit route registrations for all migrated CI4 controllers';

    public function run(array $params)
    {
        $modules = [
            'Admin' => ['prefix' => 'admin', 'filter' => "'filter' => 'admin_auth'"],
            'Cbseexam' => ['prefix' => 'cbseexam', 'filter' => "'filter' => 'admin_auth'"],
            'Accounts' => ['prefix' => 'accounts', 'filter' => "'filter' => 'admin_auth'"],
            'Onlineadmission' => ['prefix' => 'onlineadmission', 'filter' => ''],
            'User' => ['prefix' => 'user', 'filter' => ''],
        ];

        $routesFile = APPPATH . 'Config/GeneratedRoutes.php';
        $output = "<?php\n\n/** @var \\CodeIgniter\\Router\\RouteCollection \$routes */\n\n";

        foreach ($modules as $folder => $meta) {
            $path = APPPATH . 'Controllers/' . $folder;
            if (!is_dir($path)) {
                continue;
            }

            $prefix = $meta['prefix'];
            $filterStr = !empty($meta['filter']) ? ", [" . $meta['filter'] . "]" : "";

            $output .= "// Auto-generated routes for {$folder} module\n";
            $output .= "\$routes->group('{$prefix}', ['namespace' => 'App\\Controllers\\{$folder}'{$filterStr}], static function (\$routes) {\n";

            $files = glob($path . '/*Controller.php');
            foreach ($files as $file) {
                $ctrlName = basename($file, '.php');
                $slug = strtolower(str_replace('Controller', '', $ctrlName));

                $output .= "    \$routes->match(['GET', 'POST'], '{$slug}', '{$ctrlName}::index');\n";
                $output .= "    \$routes->match(['GET', 'POST'], '{$slug}/(:segment)', '{$ctrlName}::\$1');\n";
                $output .= "    \$routes->match(['GET', 'POST'], '{$slug}/(:segment)/(:any)', '{$ctrlName}::\$1/\$2');\n";
            }

            $output .= "});\n\n";
        }

        // Root level controllers
        $rootFiles = glob(APPPATH . 'Controllers/*Controller.php');
        $output .= "// Auto-generated routes for Root controllers\n";
        foreach ($rootFiles as $file) {
            $ctrlName = basename($file, '.php');
            $slug = strtolower(str_replace('Controller', '', $ctrlName));
            if ($slug === 'home' || $slug === 'base') {
                continue;
            }

            $output .= "\$routes->match(['GET', 'POST'], '{$slug}', '{$ctrlName}::index');\n";
            $output .= "\$routes->match(['GET', 'POST'], '{$slug}/(:segment)', '{$ctrlName}::\$1');\n";
            $output .= "\$routes->match(['GET', 'POST'], '{$slug}/(:segment)/(:any)', '{$ctrlName}::\$1/\$2');\n";
        }

        file_put_contents($routesFile, $output);
        CLI::write("Generated explicit routes in {$routesFile}", 'green');
    }
}
