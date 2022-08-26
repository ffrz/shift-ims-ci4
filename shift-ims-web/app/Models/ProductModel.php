<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = \App\Entities\Product::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'type', 'active', 'stock', 'uom', 'cost', 'price',
        'supplier_id', 'notes', 'category_id', 'costing_method'];

    /**
     * Check for duplicate record by name and it's ids
     */
    public function exists($name, $id)
    {
        $sql = 'select count(0) as count from products where name = :name:';
        $params = ['name' => $name];

        if ($id) {
            $sql .= ' and id <> :id:';
            $params['id'] = $id;
        }

        return $this->db->query($sql, $params)->getRow()->count != 0;
    }

    public function getAll($filter)
    {
        $sql = 'select p.*, s.name supplier_name, c.name category_name
         from products p
         left join parties s on s.id = p.supplier_id
         left join product_categories c on c.id = p.category_id
         ';
        $params = [];
        $where = [];

        if ($filter->type != 'all') {
            $where[] = 'p.type=:type:';
            $params['type'] = $filter->type;
        }

        if ($filter->active != 'all') {
            $where[] = 'p.active=:active:';
            $params['active'] = $filter->active;
        }

        if ($filter->category_id == 0) {
            $where[] = 'p.category_id is null';
        }
        else if ($filter->category_id != 'all') {
            $where[] = 'p.category_id=:category_id:';
            $params['category_id'] = $filter->category_id;
        }

        if ($filter->supplier_id == 0) {
            $where[] = 'p.supplier_id is null';
        }
        else if ($filter->supplier_id != 'all') {
            $where[] = 'p.supplier_id=:supplier_id:';
            $params['supplier_id'] = $filter->supplier_id;
        }

        if (!empty($where)) {
            $sql .= ' where ' . implode(' and ', $where);
        }
        $sql .= ' order by name asc';

        return $this->db->query($sql, $params)->getResultObject();
    }

}