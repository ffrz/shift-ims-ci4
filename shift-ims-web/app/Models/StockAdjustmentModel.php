<?php

namespace App\Models;
use App\Entities\StockUpdate;

class StockAdjustmentModel extends StockUpdateModel
{
    protected $type = StockUpdate::UPDATE_TYPE_ADJUSTMENT;
}