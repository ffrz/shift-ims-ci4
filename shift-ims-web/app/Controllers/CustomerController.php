<?php

namespace App\Controllers;

use App\Models\PartyModel;

class CustomerController extends PartyController
{
    protected $type = 1;
    protected $viewPath = "customer";
    protected $redirectUrl = "/customers";
}
