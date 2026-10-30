<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /** Permission checks for native CI4 controllers (legacy `$this->rbac` API). */
    protected \App\Libraries\NativeRbac $rbac;

    /** Legacy `$this->customlib` API (delegates to application/libraries/Customlib). */
    protected \App\Libraries\CustomlibBridge $customlib;

    /** Legacy `$this->auth` API. */
    protected $auth;

    /** Legacy `$this->lang` API. */
    protected $lang;

    /**
     * @param CLIRequest|IncomingRequest $request
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->rbac      = new \App\Libraries\NativeRbac();
        class_exists(\App\Libraries\ViewBridge::class); // CustomlibBridge is declared in ViewBridge.php
        $this->customlib = new \App\Libraries\CustomlibBridge();
        $this->auth      = new \App\Libraries\AuthBridge();
        $this->lang      = new \App\Libraries\LangBridge();
        // $this->session = service('session');
    }

    protected function respondJson($data, int $statusCode = 200)
    {
        return $this->response->setStatusCode($statusCode)->setJSON($data);
    }
}
