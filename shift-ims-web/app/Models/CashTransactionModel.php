<?php

namespace App\Models;

use CodeIgniter\Model;

class CashTransactionModel extends Model
{
    protected $table      = 'cash_transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\CashTransactionCategory::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'datetime', 'amount', 'account_id', 'description',
        'category_id', 'created_at', 'created_by', 'updated_at', 'updated_by',
        'ref_id', 'ref_type'
    ];

    public function getAll()
    {
        return $this->db->query(
            'select ct.*
                from cash_transactions ct
                order by ct.datetime desc'
        )->getResultObject();
    }
}
