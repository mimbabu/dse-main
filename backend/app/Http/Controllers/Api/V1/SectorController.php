<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Sector;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class SectorController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sectors = Sector::withCount('companies')->get();
        return $this->success('Sectors retrieved successfully', $sectors, 'sectors', Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function show(Sector $sector)
    {
        return response()->json($sector);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function companies(Sector $sector)
    {
        $companies = $sector->companies()->get();
        return $this->success('Company retrieved successfully', $companies, 'companies', Response::HTTP_OK);
    }
}
