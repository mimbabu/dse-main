<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DseController extends Controller
{
    function list()
    {
        return DB::connection('mysql2')->table('man')->get();
    }

    function idx()
    {
        return DB::connection('mysql2')->table('idx')->get();
    }

    function mki()
    {
        return DB::connection('mysql2')->table('stats')->get();
    }
}