<?php

namespace App\Entities;

class ServiceOrder extends \CodeIgniter\Entity\Entity
{
    public function __construct(?array $data = null)
    {
        $this->status = 0;
        $this->service_status = 0;   
        $this->payment_status = 0;
        $this->estimated_cost = 0;
        $this->down_payment = 0;
        $this->service_cost = 0;
        $this->parts_cost = 0;
        $this->other_cost = 0;
        $this->total_cost = 0;
        parent::__construct($data);
    }
}