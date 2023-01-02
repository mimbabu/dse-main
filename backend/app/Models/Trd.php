<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trd extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'TRD';

    protected $guarded = [];

    protected $dates = ['TRD_LM_DATE_TIME'];
}