<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'close_price',
        'ycp',
        'pe_1',
        'pe_2',
        'pe_3',
        'pe_4',
        'pe_5',
        'pe_6',
        'data_updated_at_date',
        'data_updated_at_time'
    ];

    protected $with = ['company'];

    /**
     * Get the Company that owns the PriceEarning
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
