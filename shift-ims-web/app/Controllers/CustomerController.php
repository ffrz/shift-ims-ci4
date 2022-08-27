<?php

namespace App\Controllers;

use App\Models\PartyModel;
use stdClass;

class CustomerController extends PartyController
{
    protected $type = 1;
    protected $viewPath = "customer";
    protected $redirectUrl = "/customers";

    public function view($id)
    {
        $party = $this->getPartyModel()->find($id);
        $services = $this->getServiceOrderModel()->getAllByCustomerId($party->id);
        $salesOrders = $this->getStockUpdateModel()->getAllByPartyId($party->id);
        return view('customer/view', [
            'data' => $party,
            'salesOrders' => $salesOrders,
            'services' => $services,
        ]);
    }
    
}
