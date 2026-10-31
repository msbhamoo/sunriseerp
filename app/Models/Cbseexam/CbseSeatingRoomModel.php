<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseSeatingRoomModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbse_seating_room_model.
 */
class CbseSeatingRoomModel extends BaseModel
{
    protected $table = 'cbse_seating_room';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_buildings($id = null)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'get_buildings', func_get_args());
    }

    public function add_building($data)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'add_building', func_get_args());
    }

    public function delete_building($id)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'delete_building', func_get_args());
    }

    public function get_rooms($id = null, $building_id = null)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'get_rooms', func_get_args());
    }

    public function add_room($data)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'add_room', func_get_args());
    }

    public function delete_room($id)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'delete_room', func_get_args());
    }

    public function bulk_generate_rooms($building_id, $prefix, $start, $count, $capacity, $type)
    {
        return $this->callLegacyModel('Cbse_seating_room_model', 'bulk_generate_rooms', func_get_args());
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