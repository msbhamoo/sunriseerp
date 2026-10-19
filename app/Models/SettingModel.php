<?php

namespace App\Models;

/**
 * SettingModel
 *
 * Model for school settings (`sch_settings`), session info, and general configuration.
 */
class SettingModel extends BaseModel
{
    protected $table = 'sch_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name', 'email', 'phone', 'address', 'dise_code', 'date_format',
        'currency', 'currency_symbol', 'is_rtl', 'timezone', 'session_id',
        'lang_id', 'fee_due_days', 'image', 'theme', 'biometric',
        'biometric_device', 'start_month'
    ];

    /**
     * Retrieve school settings joined with language, session, and currency.
     * Returns an object supporting both object property access (->prop) and array access (['prop']).
     */
    public function getSetting(?int $id = null)
    {
        $builder = $this->db->table('sch_settings')
            ->select('sch_settings.*, languages.language, languages.short_code, languages.short_code as language_code, sessions.session, languages.is_rtl, currencies.symbol as currency_symbol, currencies.base_price, currencies.short_name as currency, currencies.short_name as currency_name, currencies.id as currency_id')
            ->join('sessions', 'sessions.id = sch_settings.session_id', 'left')
            ->join('languages', 'languages.id = sch_settings.lang_id', 'left')
            ->join('currencies', 'currencies.id = sch_settings.currency', 'left');

        if ($id !== null) {
            $builder->where('sch_settings.id', $id);
            $result = $builder->get()->getRowArray();
            return $result ? new SettingResultBridge($result) : null;
        }

        $builder->orderBy('sch_settings.id', 'ASC');
        $results = $builder->get()->getResultArray();

        return !empty($results) ? new SettingResultBridge($results[0]) : null;
    }

    /**
     * Backwards-compatible get() method matching CI3 Setting_model::get()
     */
    public function get($id = null)
    {
        $builder = $this->db->table('sch_settings')
            ->select('sch_settings.*, languages.language, languages.short_code, languages.short_code as language_code, sessions.session, languages.is_rtl, currencies.symbol as currency_symbol, currencies.base_price, currencies.short_name as currency, currencies.short_name as currency_name, currencies.id as currency_id')
            ->join('sessions', 'sessions.id = sch_settings.session_id', 'left')
            ->join('languages', 'languages.id = sch_settings.lang_id', 'left')
            ->join('currencies', 'currencies.id = sch_settings.currency', 'left');

        if ($id !== null) {
            $builder->where('sch_settings.id', $id);
            return $builder->get()->getRowArray();
        }

        $builder->orderBy('sch_settings.id', 'ASC');
        $results = $builder->get()->getResultArray();

        if (!empty($results)) {
            $results[0]['current_session'] = [
                'session_id' => $results[0]['session_id'] ?? null,
                'session'    => $results[0]['session'] ?? null,
            ];

            $session = session();
            if ($session->has('session_array')) {
                $session_array = $session->get('session_array');
                $results[0]['session_id'] = $session_array['session_id'] ?? $results[0]['session_id'];
                $results[0]['session']    = $session_array['session'] ?? $results[0]['session'];
            }
        }

        return $results;
    }

    /**
     * Get MySQL version.
     */
    public function getMysqlVersion(): string
    {
        $row = $this->db->query('SELECT VERSION() as version')->getRow();
        return $row ? (string) $row->version : '';
    }

    /**
     * Get school timezone.
     */
    public function getTimeZone(): string
    {
        $setting = $this->getSetting();
        return !empty($setting['timezone']) ? $setting['timezone'] : 'Asia/Kolkata';
    }

    /**
     * Get school details as an object for legacy controller compatibility.
     */
    public function getSchoolDetail(?int $id = null): ?object
    {
        $setting = $this->getSetting($id);
        return $setting ? (object) $setting : (object) ['language' => 'English'];
    }
}

#[\AllowDynamicProperties]
class SettingResultBridge implements \ArrayAccess, \JsonSerializable
{
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]) || property_exists($this, $offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? ($this->$offset ?? null);
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
            $this->$offset = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
        unset($this->$offset);
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

