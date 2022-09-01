<?php

namespace App\Models;
use App\Entities\StockUpdate;
use CodeIgniter\Model;

class StockUpdateModel extends Model
{
    protected $type = 0;

    protected $table      = 'stock_updates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = StockUpdate::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'code', 'type', 'datetime', 'total_cost', 'total_price', 'party_id', 'notes', 'status',
        'created_at', 'created_by', 'lastmod_at', 'lastmod_by'
    ];

    public function getAll()
    {
        return $this->db->query('select * from stock_updates order by datetime desc')
            ->getResultObject();
    }

    public function save($entity): bool {
        if (!$entity->code) {
            $entity->code = $this->generateCode($entity->type);
        }

        return parent::save($entity);
    }

    public function generateCode($type = null)
    {
        $row = $this->db->query('select ifnull(code,0)+1 as code from stock_updates where type='
            . ($type !== null ? $type : $this->type) . ' order by code desc limit 1')
            ->getRow();
            
        if ($row) {
            return $row->code;
        }

        return 1;
    }

    public function addStockUpdate($product, $type, $balance)
    {
        $adjustment = new StockUpdate();
        $adjustment->code = $this->generateCode($type);
        $adjustment->type = $type;
        $adjustment->datetime = date('Y-m-d H:i:s');
        $adjustment->total_cost = $product->cost * $balance;
        $adjustment->total_price = $product->price * $balance;

        $this->save($adjustment);
        $this->db->query(
            "insert into stock_update_details
            ( parent_id , id , product_id , quantity , cost , price ) values
            (:parent_id:,:id:,:product_id:,:quantity:,:cost:,:price:)
            ", [
                'parent_id' => $this->db->insertId(),
                'id' => 1,
                'product_id' => $product->id,
                'quantity' => $balance,
                'cost' => $product->cost,
                'price' => $product->price
            ]);
    }

    public function revertStockUpdate($su)
    {
        // revert stock
        $items = $this->db->query(
            "select product_id, quantity
             from stock_update_details
             where parent_id=$su->id"
        )->getResultObject();
        foreach ($items as $item) {
            // revert stok dengan nilai negatif dari quantity, hanya yang tipe stocked (1) aja
            $this->db->query(
                "update products set stock=stock+(-$item->quantity) where id=$item->product_id and type=1"
            );
        }
    }

    public function getAllByPartyId($partyId)
    {
        return $this->db->query('
            select * from stock_updates
            where party_id=:party_id:
            and status=' . StockUpdate::STATUS_COMPLETED . '
            order by datetime desc',
            [ 'party_id' => $partyId])
            ->getResultObject();
    }

}