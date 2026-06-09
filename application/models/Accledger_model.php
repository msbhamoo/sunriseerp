<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accledger_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLedgerGroups()
    {
        $this->db->select('*');
        $this->db->from('acc_ledger_groups');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBanks()
    {
        $this->db->select('*');
        $this->db->from('acc_banks');
        $this->db->where('is_active', 1);
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function addLedger($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('acc_ledgers', $data);
            $insert_id = $data['id'];
        } else {
            $this->db->insert('acc_ledgers', $data);
            $insert_id = $this->db->insert_id();
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $insert_id;
    }

    public function getLedgers($id = null)
    {
        $this->db->select('acc_ledgers.*, acc_ledger_groups.name as group_name, acc_banks.name as bank_name');
        $this->db->from('acc_ledgers');
        $this->db->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id', 'left');
        $this->db->join('acc_banks', 'acc_banks.id = acc_ledgers.bank_id', 'left');
        
        if ($id != null) {
            $this->db->where('acc_ledgers.id', $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->order_by('acc_ledgers.name', 'asc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getLedgersBySystemGroup($system_name)
    {
        $this->db->select('acc_ledgers.*, acc_ledger_groups.system_name');
        $this->db->from('acc_ledgers');
        $this->db->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id');
        $this->db->where('acc_ledger_groups.system_name', $system_name);
        $this->db->order_by('acc_ledgers.name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLedgersBySystemGroups($system_names)
    {
        $this->db->select('acc_ledgers.*, acc_ledger_groups.system_name');
        $this->db->from('acc_ledgers');
        $this->db->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id');
        $this->db->where_in('acc_ledger_groups.system_name', $system_names);
        $this->db->order_by('acc_ledgers.name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLedgersByGroupIds($group_ids)
    {
        $this->db->select('acc_ledgers.*, acc_ledger_groups.system_name');
        $this->db->from('acc_ledgers');
        $this->db->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id');
        $this->db->where_in('acc_ledgers.group_id', $group_ids);
        $this->db->order_by('acc_ledgers.name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function deleteLedger($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('acc_ledgers');
    }

    public function getDatatableLedgers()
    {
        $this->datatables
            ->select('acc_ledgers.id, acc_ledgers.name, acc_ledgers.mobile, acc_ledgers.opening_balance, acc_ledgers.opening_type, acc_ledger_groups.name as group_name')
            ->from('acc_ledgers')
            ->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id', 'left')
            ->searchable('acc_ledgers.name, acc_ledger_groups.name, acc_ledgers.mobile')
            ->orderable('acc_ledgers.name, acc_ledger_groups.name, acc_ledgers.mobile, acc_ledgers.opening_balance')
            ->sort('acc_ledgers.id', 'desc');

        return $this->datatables->generate('json');
    }

    public function seedDefaultData()
    {
        // 0. Auto-upgrade schema for live servers that missed the db.sql update
        if (!$this->db->field_exists('mobile', 'acc_ledgers')) {
            $this->db->query("ALTER TABLE `acc_ledgers` 
                ADD COLUMN `mobile` varchar(20) DEFAULT NULL AFTER `name`,
                ADD COLUMN `email` varchar(100) DEFAULT NULL AFTER `mobile`,
                ADD COLUMN `address` text AFTER `email`,
                ADD COLUMN `state` varchar(100) DEFAULT NULL AFTER `address`,
                ADD COLUMN `gst_no` varchar(50) DEFAULT NULL AFTER `state`,
                ADD COLUMN `pan_no` varchar(50) DEFAULT NULL AFTER `gst_no`,
                ADD COLUMN `aadhar_no` varchar(50) DEFAULT NULL AFTER `pan_no`,
                ADD COLUMN `bank_id` int(11) DEFAULT NULL AFTER `aadhar_no`,
                ADD COLUMN `account_no` varchar(50) DEFAULT NULL AFTER `bank_id`,
                ADD COLUMN `ifsc_code` varchar(20) DEFAULT NULL AFTER `account_no`,
                ADD COLUMN `branch` varchar(100) DEFAULT NULL AFTER `ifsc_code`
            ");
        }

        // 1. Check if expense types are empty
        $existing_exp = $this->db->select('name')->get('acc_expense_types')->result_array();
        $existing_exp_names = array_column($existing_exp, 'name');
        
        $default_types = [
            // Incomes
            ['name' => 'Tuition Fees', 'type' => 'income', 'is_active' => 1],
            ['name' => 'Admission Fees', 'type' => 'income', 'is_active' => 1],
            ['name' => 'Transport Fees', 'type' => 'income', 'is_active' => 1],
            ['name' => 'Bookstore Income', 'type' => 'income', 'is_active' => 1],
            ['name' => 'Canteen Sales', 'type' => 'income', 'is_active' => 1],
            ['name' => 'Hostel Fees', 'type' => 'income', 'is_active' => 1],
            ['name' => 'Miscellaneous Income', 'type' => 'income', 'is_active' => 1],
            // Expenses
            ['name' => 'Teacher Salaries', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Staff Salaries', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Electricity & Water Utilities', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Rent & Taxes', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Printing & Stationery', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Repairs & Maintenance', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Canteen Expenses', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Vehicle Fuel & Transport Maintenance', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Library Books & Periodicals', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Laboratory Expenses', 'type' => 'expense', 'is_active' => 1],
            ['name' => 'Sports & Games Expenses', 'type' => 'expense', 'is_active' => 1]
        ];
        
        $types_to_insert = [];
        foreach ($default_types as $type) {
            if (!in_array($type['name'], $existing_exp_names)) {
                $types_to_insert[] = $type;
            }
        }
        if (!empty($types_to_insert)) {
            $this->db->insert_batch('acc_expense_types', $types_to_insert);
        }

        // 2. Check and seed ledgers individually
        $existing_ledgers = $this->db->select('name')->get('acc_ledgers')->result_array();
        $existing_ledger_names = array_column($existing_ledgers, 'name');
        
        $groups = $this->db->select('id, system_name')->get('acc_ledger_groups')->result_array();
        $group_ids = [];
        foreach ($groups as $g) {
            $group_ids[$g['system_name']] = $g['id'];
        }

        $default_ledgers = [
            [
                'group_id' => isset($group_ids['cash']) ? $group_ids['cash'] : 2,
                'name' => 'Cash Account',
                'opening_type' => 'Dr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['bank']) ? $group_ids['bank'] : 1,
                'name' => 'Main Bank Account',
                'bank_id' => 1,
                'account_no' => '1234567890',
                'ifsc_code' => 'SBIN0001234',
                'opening_type' => 'Dr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['sundry_debtors']) ? $group_ids['sundry_debtors'] : 3,
                'name' => 'School Fees Receivable',
                'opening_type' => 'Dr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['current_liabilities']) ? $group_ids['current_liabilities'] : 15,
                'name' => 'General Salaries Payable',
                'opening_type' => 'Cr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['sundry_creditors']) ? $group_ids['sundry_creditors'] : 4,
                'name' => 'General Vendors / Creditors',
                'opening_type' => 'Cr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['direct_income']) ? $group_ids['direct_income'] : 9,
                'name' => 'Tuition Fees Collection',
                'opening_type' => 'Cr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['indirect_income']) ? $group_ids['indirect_income'] : 10,
                'name' => 'Canteen & Store Income',
                'opening_type' => 'Cr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['indirect_expense']) ? $group_ids['indirect_expense'] : 8,
                'name' => 'Utilities & Rent Expense Account',
                'opening_type' => 'Dr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['indirect_expense']) ? $group_ids['indirect_expense'] : 8,
                'name' => 'Printing, Stationery & Supplies',
                'opening_type' => 'Dr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
            [
                'group_id' => isset($group_ids['indirect_expense']) ? $group_ids['indirect_expense'] : 8,
                'name' => 'Staff Salaries Expense Account',
                'opening_type' => 'Dr',
                'opening_balance' => 0.00,
                'opening_date' => date('Y-04-01')
            ],
        ];
        
        $ledgers_to_insert = [];
        foreach ($default_ledgers as $ledger) {
            if (!in_array($ledger['name'], $existing_ledger_names)) {
                $ledgers_to_insert[] = $ledger;
            }
        }
        
        if (!empty($ledgers_to_insert)) {
            $this->db->insert_batch('acc_ledgers', $ledgers_to_insert);
        }
    }
}

