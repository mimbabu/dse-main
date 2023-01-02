<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mkistat extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'MKISTAT';
    protected $guarded = [];
}
