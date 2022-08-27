<?php

namespace App\Controllers;

use App\Models\PartyModel;

class SupplierController extends PartyController
{
    protected $type = 2;
    protected $viewPath = 'supplier';
    protected $redirectUrl = "/suppliers";

    public function view($id)
    {
        $party = $this->getPartyModel()->find($id);
        $salesOrders = $this->getStockUpdateModel()->getAllByPartyId($party->id);
        return view('supplier/view', [
            'data' => $party,
            'salesOrders' => $salesOrders,
        ]);
    }
}
