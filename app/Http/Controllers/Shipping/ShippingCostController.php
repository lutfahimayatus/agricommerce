<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shipping\ShippingCostCollection;
use App\Models\ShippingCost;

class ShippingCostController extends Controller
{
    public function index()
    {
        return new ShippingCostCollection(ShippingCost::all());
    }
}
