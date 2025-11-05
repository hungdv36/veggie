<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSale = FlashSale::with(['items.product'])
            ->where('status', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        return view('clients.pages.flash-sale', compact('flashSale'));
    }
}

