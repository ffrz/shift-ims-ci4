<?php

namespace App\Models;

use App\Entities\OrderPayment;
use CodeIgniter\Model;

class OrderPaymentModel extends Model
{
    protected $type = 0;

    protected $table      = 'order_payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = OrderPayment::class;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'date', 'amount', 'created_at', 'created_by', 'update_id'
    ];

    public function addPayment($orderId, $date, $amount)
    {
        $payment = new OrderPayment();
        $payment->date = $date;
        $payment->amount = $amount;
        $payment->created_at = date('Y-m-d H:i:s');
        $payment->created_by = current_user()->username;
        $payment->update_id = $orderId;

        $this->save($payment);
    }
}