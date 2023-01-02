<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\DayEndSummary;
use Symfony\Component\HttpFoundation\Response;


class CompanyController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return $this->success('Companies retrieved successfully', $companies, 'companies', Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function dayEndSummary(Company $company)
    {
        $dayEndSummaries = DayEndSummary::where('company_id', $company->id)->get();
        return response()->json($dayEndSummaries);
    }
}