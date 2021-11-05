<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Catalog;
use App\Models\Manufacturer;
use App\Models\Slider;
use Inertia\Inertia;
use \Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class HomeController extends Controller
{

    public function store()
    {
        return Inertia::render('Web/Main', [
            'slider' => Slider::images(),
            'catalog' => Catalog::getCatalog(12),
            'manufacturers' => Manufacturer::images(),
            'addresses' => Address::get(),
        ]);
    }
}
