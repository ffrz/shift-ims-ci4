<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table      = 'product_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = \App\Entities\ProductCategory::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name'];

    /**
     * Periksa duplikat rekaman berdasarkan nama dan id
     * @var $name nama produk
     * @var $id id produk
     * @return bool true jika ada duplikat, false jika tidak ada duplikat 
     */
    public function exists($name, $id)
    {
        $sql = 'select count(0) as count from product_categories where name = :name:';
        $params = ['name' => $name];

        if ($id) {
            $sql .= ' and id <> :id:';
            $params['id'] = $id;
        }

        return $this->db->query($sql, $params)->getRow()->count != 0;
    }

    public function getAll()
    {
        return $this->db->query('select * from product_categories order by name asc')
            ->getResultObject();
    }

    public function getAllWithProductCount()
    {
        return $this->db->query(
            'select c.*, ifnull((select count(0) from products p where p.category_id=c.id), 0) count
            from product_categories c order by name asc')
            ->getResultObject();
    }

}