<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\DayEndSummary;
use App\Http\Traits\StoreDayEndSummaryTrait;

class DayEndSummaryController extends Controller
{
    use StoreDayEndSummaryTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->storeLatestDayEndSummary();
        return DayEndSummary::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dt = Carbon::now('Asia/Dhaka')->subYears(2)->toDateString();
        $this->storeDayEndSummary($dt);
        return DayEndSummary::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DayEndSummary  $dayEndSummary
     * @return \Illuminate\Http\Response
     */
    public function show(DayEndSummary $dayEndSummary)
    {
        //
    }
}
