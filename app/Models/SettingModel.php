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
     */
    public function getSetting(?int $id = null): ?array
    {
        $builder = $this->db->table('sch_settings')
            ->select('sch_settings.*, languages.language, languages.short_code as language_code, sessions.session, languages.is_rtl, currencies.symbol as currency_symbol, currencies.base_price, currencies.short_name as currency_name, currencies.id as currency_id')
            ->join('sessions', 'sessions.id = sch_settings.session_id', 'left')
            ->join('languages', 'languages.id = sch_settings.lang_id', 'left')
            ->join('currencies', 'currencies.id = sch_settings.currency', 'left');

        if ($id !== null) {
            $builder->where('sch_settings.id', $id);
            $result = $builder->get()->getRowArray();
            return $result ?: null;
        }

        $builder->orderBy('sch_settings.id', 'ASC');
        $results = $builder->get()->getResultArray();

        return !empty($results) ? $results[0] : null;
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
