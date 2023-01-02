<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LatestSharePrice;
use App\Http\Traits\StoreLatestSharePriceTrait;

class LatestSharePriceController extends Controller
{
    // use StoreLatestSharePriceTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(LatestSharePrice::all());
        return LatestSharePrice::all();

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->storeLatestSharePrice();
        return LatestSharePrice::all();
    }
}
