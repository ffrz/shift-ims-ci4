<?php

namespace App\Controllers;

use App\Models\PartyModel;

class SupplierController extends PartyController
{
    protected $type = 2;
    protected $viewPath = 'supplier';
    protected $redirectUrl = "/suppliers";
}
