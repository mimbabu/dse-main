<?php

namespace App\Console;

use App\Http\Traits\DseLiveData;
use App\Http\Traits\StoreCompanySectorTrait;
use App\Http\Traits\CircuitBreakerTrait;
use App\Http\Traits\DataScrapeTrait;
use Carbon\Carbon;
use App\Http\Traits\StorePriceEarningTrait;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Traits\StoreDayEndSummaryTrait;
use App\Http\Traits\StoreLatestSharePriceTrait;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use StoreLatestSharePriceTrait, StorePriceEarningTrait, StoreDayEndSummaryTrait, StoreCompanySectorTrait, CircuitBreakerTrait, DseLiveData, DataScrapeTrait;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->exec("php artisan mdsdata:store");
        // $schedule->call(function () {
        //     $now = Carbon::now();
        //     // if ($now->minute == 15 || $now->minute == 45) {
        //         $this->storeLatestSharePrice();
        //         $this->storePriceEarning();
        //         $this->storeLatestDayEndSummary();
        //         $this->storeCompanySector();

        //     // }
        // })->everyMinute()->between('10:01', '18:50');
        $schedule->call(function () {
            // scraping
            $this->storeLatestSharePrice();
            // $this->storePriceEarning();
            $this->storeLatestDayEndSummary();
            $this->storeCompanySector();
            $this->circuitfoo();
            // from DSE IMDS data
            $this->fetchTrdDataAndStore();
            $this->fetchMANDataAndStore();
            $this->fetchMkistatDataAndStore();
            $this->fetchStatsDataAndStore();
            $this->fetchIDXDataAndStore();
            // $url = "https://dsebd.org/displayCompany.php?name=";
            // $this->scrapeCompany($url);
        })->everyMinute();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}