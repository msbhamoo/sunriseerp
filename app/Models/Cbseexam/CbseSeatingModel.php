<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseSeatingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbse_seating_model.
 */
class CbseSeatingModel extends BaseModel
{
    protected $table = 'cbse_seating';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_allocations($exam_id = null)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'get_allocations', func_get_args());
    }

    public function get_allocation_by_id($id)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'get_allocation_by_id', func_get_args());
    }

    public function autoAllocateStudents($exam_id, $exam_date, $strategy, $seat_format, $column_depth, $selected_rooms)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'autoAllocateStudents', func_get_args());
    }

    public function delete_allocation($id)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'delete_allocation', func_get_args());
    }

    public function update_allocation_status($id, $status)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'update_allocation_status', func_get_args());
    }

    public function swap_students($seat1_id, $seat2_id)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'swap_students', func_get_args());
    }

    public function move_students($seat_ids_array, $target_room_assignment_id)
    {
        return $this->callLegacyModel('Cbse_seating_model', 'move_students', func_get_args());
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
        return 'cbseexam';
    }
}