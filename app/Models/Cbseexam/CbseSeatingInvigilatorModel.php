<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseSeatingInvigilatorModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbse_seating_invigilator_model.
 */
class CbseSeatingInvigilatorModel extends BaseModel
{
    protected $table = 'cbse_seating_invigilator';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_allocation_rooms($allocation_id)
    {
        return $this->callLegacyModel('Cbse_seating_invigilator_model', 'get_allocation_rooms', func_get_args());
    }

    public function assign_invigilators($allocation_id, $data)
    {
        return $this->callLegacyModel('Cbse_seating_invigilator_model', 'assign_invigilators', func_get_args());
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
        return 'Cbseexam';
    }
}