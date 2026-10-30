<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * CbseadmitcardbulkController
 *
 * Auto-generated CI4 controller migrating CI3 Cbseadmitcardbulk.
 */
class CbseadmitcardbulkController extends BaseController
{
    public function generate()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'generate', func_get_args());
    }

    public function generate_missing()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'generate_missing', func_get_args());
    }

    public function generate_missing_by_section()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'generate_missing_by_section', func_get_args());
    }

    public function generate_admitcards()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'generate_admitcards', func_get_args());
    }

    public function save_and_download()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'save_and_download', func_get_args());
    }

    public function reset_generated()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'reset_generated', func_get_args());
    }

    public function download_all()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'download_all', func_get_args());
    }

    public function view_admitcard_html()
    {
        return $this->dispatchLegacy('Cbseadmitcardbulk', 'view_admitcard_html', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/cbseexam/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}