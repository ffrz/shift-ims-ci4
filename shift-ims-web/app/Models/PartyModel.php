<?php

namespace App\Models;

use CodeIgniter\Model;

class PartyModel extends Model
{
    protected $table      = 'parties';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = \App\Entities\Party::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'address', 'url', 'active', 'contacts', 'type'];

    /**
     * Check for duplicate record by name and it's ids
     */
    public function exists($name, $id, $type)
    {
        $sql = 'select count(0) as count from parties where name=:name: and type=' . $type;
        $params = ['name' => $name];

        if ($id) {
            $sql .= ' and id<>:id:';
            $params['id'] = $id;
        }

        return $this->db->query($sql, $params)->getRow()->count != 0;
    }

    public function getAll($type)
    {
        return $this->db->query('select * from parties where type=' . $type . ' order by name asc')
            ->getResultObject();
    }

    public function getAllCustomers()
    {
        return $this->getAll(1);
    }

    public function getAllSuppliers()
    {
        return $this->getAll(2);
    }

}