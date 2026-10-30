<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SharecontentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Sharecontent_model.
 */
class SharecontentModel extends BaseModel
{
    protected $table = 'sharecontent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Sharecontent_model', 'get', func_get_args());
    }

    public function checkvalid($upload_content_id, $share_content_id)
    {
        return $this->callLegacyModel('Sharecontent_model', 'checkvalid', func_get_args());
    }

    public function getShareContentWithDocuments($id = null)
    {
        return $this->callLegacyModel('Sharecontent_model', 'getShareContentWithDocuments', func_get_args());
    }

    public function getShareContentDocumentsByID($share_content_id = null)
    {
        return $this->callLegacyModel('Sharecontent_model', 'getShareContentDocumentsByID', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Sharecontent_model', 'remove', func_get_args());
    }

    public function add($data, $share_content_data, $selected_contents)
    {
        return $this->callLegacyModel('Sharecontent_model', 'add', func_get_args());
    }

    public function getsharelist()
    {
        return $this->callLegacyModel('Sharecontent_model', 'getsharelist', func_get_args());
    }

    public function getOtherStaffsharelist($role_id,$staff_id)
    {
        return $this->callLegacyModel('Sharecontent_model', 'getOtherStaffsharelist', func_get_args());
    }

    public function getSharedUserBySharedID($share_content_id)
    {
        return $this->callLegacyModel('Sharecontent_model', 'getSharedUserBySharedID', func_get_args());
    }

    public function getStudentsharelist($student_id,$class_id,$section_id)
    {
        return $this->callLegacyModel('Sharecontent_model', 'getStudentsharelist', func_get_args());
    }

    public function getParentsharelist($user_parent_id,$class_id,$section_id)
    {
        return $this->callLegacyModel('Sharecontent_model', 'getParentsharelist', func_get_args());
    }

    /**
     * Compatibility Bridge: Delegates execution to the legacy model instance
     * maintaining full compatibility with query builder and CI3 environment.
     */
    protected function callLegacyModel(string $modelClass, string $method, array $args)
    {
        static $instances = [];

        if (!isset($instances[$modelClass])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            $file = APPPATH . '../application/models/' . ($this->subPath() ? $this->subPath() . '/' : '') . $modelClass . '.php';
            if (file_exists($file)) {
                require_once $file;
                $instances[$modelClass] = new $modelClass();
            } else {
                return null;
            }
        }

        return call_user_func_array([$instances[$modelClass], $method], $args);
    }

    protected function subPath(): string
    {
        return '';
    }
}