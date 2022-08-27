<?php

namespace App\Models;
use App\Entities\StockUpdateDetail;
use CodeIgniter\Model;

class StockUpdateDetailModel extends Model
{
    protected $type = 0;

    protected $table      = 'stock_update_details';
    // protected $primaryKey = ['parent_id', 'id'];
    protected $useAutoIncrement = true;
    protected $returnType     = StockUpdateDetail::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id', 'parent_id', 'product_id', 'cost', 'price', 'quantity'
    ];

    public function getAllByUpdateId($id) 
    {
        return $this->db->query(
            "select d.id, d.quantity, d.cost, d.price,
            p.name, p.uom
            from stock_update_details d
            inner join products p on p.id=d.product_id
            where d.parent_id=$id
            order by d.id asc"
        )->getResultObject();

    }
}