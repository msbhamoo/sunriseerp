<?php

namespace Config;

use App\Libraries\BridgedView;
use CodeIgniter\Config\BaseService;
use Config\View as ViewConfig;

/**
 * Services Configuration file.
 */
class Services extends BaseService
{
    /**
     * The Renderer class is the default concrete class that implements
     * the View interface. Returns BridgedView for CI3 compatibility.
     *
     * @return BridgedView
     */
    public static function renderer(?string $viewPath = null, ?ViewConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('renderer', $viewPath, $config);
        }

        $viewPath = in_array($viewPath, [null, '', '0'], true) ? (new Paths())->viewDirectory : $viewPath;
        $config ??= config(ViewConfig::class);

        return new BridgedView($config, $viewPath, static::get('locator'), CI_DEBUG, static::get('logger'));
    }
}
