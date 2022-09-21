<?php

namespace App\Models;

use CodeIgniter\Model;

class CashAccountModel extends Model
{
    protected $table      = 'cash_accounts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\CashAccount::class;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['name', 'balance'];

    public function getAll()
    {
        return $this->db->query('
            select c.*
                from cash_accounts c
                order by c.name asc'
            )->getResultObject();
    }

    public function exists($name, $id)
    {
        $sql = 'select count(0) as count
            from cash_accounts c
            where name=:name:';
        $params = ['name' => $name];
        if ($id) {
            $sql .= ' and id <> :id:';
            $params['id'] = $id;
        }

        return $this->db->query($sql, $params)->getRow()->count != 0;
    }
}