<?php

namespace App\Http\Controllers;

use App\Models\PriceEarning;
use Illuminate\Http\Request;
use App\Http\Traits\StorePriceEarningTrait;

class PriceEarningController extends Controller
{
    use StorePriceEarningTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PriceEarning::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->storePriceEarning();
        return PriceEarning::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceEarning  $priceEarning
     * @return \Illuminate\Http\Response
     */
    public function show(PriceEarning $priceEarning)
    {
        //
    }
}