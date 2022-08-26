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
}