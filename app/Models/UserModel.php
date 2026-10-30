<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * UserModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy User_model.
 */
class UserModel extends BaseModel
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('User_model', 'add', func_get_args());
    }

    public function addNewParent($data_parent_login, $student_data)
    {
        return $this->callLegacyModel('User_model', 'addNewParent', func_get_args());
    }

    public function checkLogin($data)
    {
        return $this->callLegacyModel('User_model', 'checkLogin', func_get_args());
    }

    public function checkLoginParent($data)
    {
        return $this->callLegacyModel('User_model', 'checkLoginParent', func_get_args());
    }

    public function read_user_information($users_id)
    {
        return $this->callLegacyModel('User_model', 'read_user_information', func_get_args());
    }

    public function read_teacher_information($users_id)
    {
        return $this->callLegacyModel('User_model', 'read_teacher_information', func_get_args());
    }

    public function read_accountant_information($users_id)
    {
        return $this->callLegacyModel('User_model', 'read_accountant_information', func_get_args());
    }

    public function read_librarian_information($users_id)
    {
        return $this->callLegacyModel('User_model', 'read_librarian_information', func_get_args());
    }

    public function checkOldUsername($data)
    {
        return $this->callLegacyModel('User_model', 'checkOldUsername', func_get_args());
    }

    public function checkOldPass($data)
    {
        return $this->callLegacyModel('User_model', 'checkOldPass', func_get_args());
    }

    public function checkUserNameExist($data)
    {
        return $this->callLegacyModel('User_model', 'checkUserNameExist', func_get_args());
    }

    public function saveNewPass($data)
    {
        return $this->callLegacyModel('User_model', 'saveNewPass', func_get_args());
    }

    public function changeStatus($data)
    {
        return $this->callLegacyModel('User_model', 'changeStatus', func_get_args());
    }

    public function saveNewUsername($data)
    {
        return $this->callLegacyModel('User_model', 'saveNewUsername', func_get_args());
    }

    public function read_user()
    {
        return $this->callLegacyModel('User_model', 'read_user', func_get_args());
    }

    public function read_single_child($child_id)
    {
        return $this->callLegacyModel('User_model', 'read_single_child', func_get_args());
    }

    public function getLoginDetails($student_id)
    {
        return $this->callLegacyModel('User_model', 'getLoginDetails', func_get_args());
    }

    public function getStudentLoginDetails($student_id)
    {
        return $this->callLegacyModel('User_model', 'getStudentLoginDetails', func_get_args());
    }

    public function getTeacherLoginDetails($teacher_id)
    {
        return $this->callLegacyModel('User_model', 'getTeacherLoginDetails', func_get_args());
    }

    public function getLibrarianLoginDetails($librarian_id)
    {
        return $this->callLegacyModel('User_model', 'getLibrarianLoginDetails', func_get_args());
    }

    public function getAccountantLoginDetails($accountant_id)
    {
        return $this->callLegacyModel('User_model', 'getAccountantLoginDetails', func_get_args());
    }

    public function updateVerCode($data)
    {
        return $this->callLegacyModel('User_model', 'updateVerCode', func_get_args());
    }

    public function getUserByEmail($table, $role, $email)
    {
        return $this->callLegacyModel('User_model', 'getUserByEmail', func_get_args());
    }

    public function getParentByEmail($table, $role, $email)
    {
        return $this->callLegacyModel('User_model', 'getParentByEmail', func_get_args());
    }

    public function getUserValidCode($table, $role, $code)
    {
        return $this->callLegacyModel('User_model', 'getUserValidCode', func_get_args());
    }

    public function getParentUserValidCode($table, $role, $code)
    {
        return $this->callLegacyModel('User_model', 'getParentUserValidCode', func_get_args());
    }

    public function forgotPassword($usertype, $email)
    {
        return $this->callLegacyModel('User_model', 'forgotPassword', func_get_args());
    }

    public function getUserByCodeUsertype($usertype, $code)
    {
        return $this->callLegacyModel('User_model', 'getUserByCodeUsertype', func_get_args());
    }

    public function getUserLoginDetails($student_id)
    {
        return $this->callLegacyModel('User_model', 'getUserLoginDetails', func_get_args());
    }

    public function getParentLoginDetails($student_id)
    {
        return $this->callLegacyModel('User_model', 'getParentLoginDetails', func_get_args());
    }

    public function student_information($users_id)
    {
        return $this->callLegacyModel('User_model', 'student_information', func_get_args());
    }

    public function get_studentdefaultClass($student_id)
    {
        return $this->callLegacyModel('User_model', 'get_studentdefaultClass', func_get_args());
    }

    public function getById($id)
    {
        return $this->callLegacyModel('User_model', 'getById', func_get_args());
    }

    public function studentLogout($id, $data)
    {
        return $this->callLegacyModel('User_model', 'studentLogout', func_get_args());
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