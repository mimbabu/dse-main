<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DayEndSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'ltp',
        'high',
        'low',
        'open_price',
        'close_price',
        'ycp',
        'trade',
        'value_mn',
        'volume',
        'data_updated_at_date'
    ];

    protected $with = ['company'];

    /**
     * Get the Company that owns the DayEndSummary
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
