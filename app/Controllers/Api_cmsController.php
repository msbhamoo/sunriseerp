<?php

namespace App\Controllers\.;

use App\Controllers\BaseController;

/**
 * Api_cmsController
 *
 * Auto-generated CI4 controller migrating CI3 Api_cms.
 */
class Api_cmsController extends BaseController
{
    public function events()
    {
        return $this->dispatchLegacy('Api_cms', 'events', func_get_args());
    }

    public function notices()
    {
        return $this->dispatchLegacy('Api_cms', 'notices', func_get_args());
    }

    public function pages()
    {
        return $this->dispatchLegacy('Api_cms', 'pages', func_get_args());
    }

    public function news()
    {
        return $this->dispatchLegacy('Api_cms', 'news', func_get_args());
    }

    public function news_item($slug)
    {
        return $this->dispatchLegacy('Api_cms', 'news_item', func_get_args());
    }

    public function page($slug)
    {
        return $this->dispatchLegacy('Api_cms', 'page', func_get_args());
    }

    public function gallery()
    {
        return $this->dispatchLegacy('Api_cms', 'gallery', func_get_args());
    }

    public function gallery_item($slug)
    {
        return $this->dispatchLegacy('Api_cms', 'gallery_item', func_get_args());
    }

    public function verify_tc()
    {
        return $this->dispatchLegacy('Api_cms', 'verify_tc', func_get_args());
    }

    public function alumni_stories()
    {
        return $this->dispatchLegacy('Api_cms', 'alumni_stories', func_get_args());
    }

    public function job_postings()
    {
        return $this->dispatchLegacy('Api_cms', 'job_postings', func_get_args());
    }

    public function job_item($id)
    {
        return $this->dispatchLegacy('Api_cms', 'job_item', func_get_args());
    }

    public function track_job_view()
    {
        return $this->dispatchLegacy('Api_cms', 'track_job_view', func_get_args());
    }

    public function submit_job_application()
    {
        return $this->dispatchLegacy('Api_cms', 'submit_job_application', func_get_args());
    }

    public function active_scholarship_exam()
    {
        return $this->dispatchLegacy('Api_cms', 'active_scholarship_exam', func_get_args());
    }

    public function scholarship_exams()
    {
        return $this->dispatchLegacy('Api_cms', 'scholarship_exams', func_get_args());
    }

    public function scholarship_exam_item($id = null)
    {
        return $this->dispatchLegacy('Api_cms', 'scholarship_exam_item', func_get_args());
    }

    public function register_scholarship_candidate()
    {
        return $this->dispatchLegacy('Api_cms', 'register_scholarship_candidate', func_get_args());
    }

    public function scholarship_result_search()
    {
        return $this->dispatchLegacy('Api_cms', 'scholarship_result_search', func_get_args());
    }

    public function cbse_disclosure()
    {
        return $this->dispatchLegacy('Api_cms', 'cbse_disclosure', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/./' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}