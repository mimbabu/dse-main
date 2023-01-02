<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'company_name',
        'market_capitalization_mn',
    ];

    protected $with = ['sector'];


    /**
     * Get all of the Latest Share Prices for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function latestSharePrices(): HasMany
    {
        return $this->hasMany(LatestSharePrice::class);
    }

    /**
     * Get all of the Price Earnings for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function priceEarnings(): HasMany
    {
        return $this->hasMany(PriceEarning::class);
    }

    /**
     * Get all of the Day End Summaries for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dayEndSummaries(): HasMany
    {
        return $this->hasMany(DayEndSummary::class);
    }

    /**
     * Get the Sector that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}