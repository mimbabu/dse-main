<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LatestSharePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'ltp',
        'high',
        'low',
        'close_price',
        'ycp',
        'change',
        'trade',
        'value_mn',
        'volume',
        'data_updated_at_date',
        'data_updated_at_time'
    ];

    protected $with = ['company'];

    /**
     * Get the Company that owns the LatestSharePrice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
