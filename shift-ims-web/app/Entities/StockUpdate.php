<?php

namespace App\Entities;

class StockUpdate extends \CodeIgniter\Entity\Entity
{
    const UPDATE_TYPE_INITIAL_STOCK = 0;
    const UPDATE_TYPE_MANUAL_AJDUSTMENT = 1;
    const UPDATE_TYPE_ADJUSTMENT = 2;

    const UPDATE_TYPE_PURCHASE_ORDER = 11;
    const UPDATE_TYPE_PURCHASE_ORDER_RETURN = 12;

    const UPDATE_TYPE_SALES_ORDER = 21;
    const UPDATE_TYPE_SALES_ORDER_RETURN = 22;

    const STATUS_SAVED = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELED = 2;

    const PAYMENTSTATUS_UNPAID = 0;
    const PAYMENTSTATUS_PARTIALLYPAID = 1;
    const PAYMENTSTATUS_FULLYPAID = 2;
}