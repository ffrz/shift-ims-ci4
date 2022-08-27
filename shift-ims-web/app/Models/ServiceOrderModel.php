<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceOrderModel extends Model
{
    protected $table      = 'service_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = \App\Entities\ServiceOrder::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'date', 'status',
        'customer_id', 'customer_name', 'customer_address', 'customer_contacts',
        'device', 'accessories', 'problems', 'damages', 'actions', 'service_status',
        'estimated_cost', 'down_payment',
        'parts_cost', 'service_cost', 'other_cost', 'total_cost', 'payment_status',
        'notes'
    ];

    public function getAllByCustomerId($id)
    {
        return $this->db->query(
            'select * from service_orders where customer_id=:id: order by date desc', ['id' => $id]
        )->getResultObject();
    }

    public function getAll($filter)
    {
        $sql = 'select * from service_orders';
        $params = [];
        $where = [];

        if ($filter->order_status != -1) {
            $where[] = 'status=:status:';
            $params['status'] = $filter->order_status;
        }
        if ($filter->service_status != -1) {
            $where[] = 'service_status=:service_status:';
            $params['service_status'] = $filter->service_status;
        }
        if ($filter->payment_status != -1) {
            $where[] = 'payment_status=:payment_status:';
            $params['payment_status'] = $filter->payment_status;
        }

        if (!empty($where)) {
            $sql .= ' where ' . implode(' and ', $where);
        }
        $sql .= ' order by date desc';

        return $this->db->query($sql, $params)->getResultObject();
    }

}