<?php

namespace App\Entities;

class Product extends \CodeIgniter\Entity\Entity
{
    const TYPE_NON_STOCKED = 0;
    const TYPE_STOCKED = 1;
    const TYPE_SERVICE = 2;

    const COSTING_METHOD_MANUAL = 0;
    const COSTING_METHOD_LAST = 1;
    const COSTING_METHOD_AVERAGE = 2;
    
    public function __construct(?array $data = null)
    {
        $this->type = 0;
        $this->stock = 0;   
        $this->cost = 0;
        $this->price = 0;
        $this->active = true;
        $this->costing_method = 0;
        parent::__construct($data);
    }
}