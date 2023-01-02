<?php

namespace App\Console\Commands;

use App\Models\Idx;
use App\Models\Man;
use App\Models\Mkistat;
use App\Models\Stats;
use App\Models\Trd;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class StoreDSELiveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mdsdata:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store DSE Live Data (mdsdata) perdiodically';

    /**
     * Execute the console command.
     *
     * @param
     * @return mixed
     */
    public function handle()
    {
        $this->storeAllTableData();
    }

    public function storeAllTableData()
    {
        $this->fetchIDXDataAndStore();
        $this->fetchMANDataAndStore();
        $this->fetchMkistatDataAndStore();
        $this->fetchStatsDataAndStore();
        $this->fetchTrdDataAndStore();
    }

    public function fetchTrdDataAndStore()
    {

        try {
            $trd = Trd::on('mysql_dse_live')->get();
            // DB::enableQueryLog();
            // $trd = Trd::get();
            // $query = DB::getQueryLog();
            // dd($query);
            Trd::where("TRD_LM_DATE_TIME", date("Y-m-d"))->delete();

            foreach ($trd as $ix) {

                $now = Carbon::now();
                $TRD_LM_DATE_TIME = $ix->TRD_LM_DATE_TIME;
                $TRD_LM_DATE_TIME->hour = $now->hour;
                $TRD_LM_DATE_TIME->minute = $now->minute;
                $TRD_LM_DATE_TIME->second = $now->second;
                Trd::firstOrCreate([
                    'TRD_SNO' => $ix->TRD_LM_DATE_TIME,
                    'TRD_TOTAL_TRADES' => $ix->TRD_TOTAL_TRADES,
                    'TRD_TOTAL_VOLUME' => $ix->TRD_TOTAL_VOLUME,
                    'TRD_TOTAL_VALUE' => $ix->TRD_TOTAL_VALUE,
                    'TRD_LM_DATE_TIME' => $TRD_LM_DATE_TIME
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "TRD::";
            var_dump($e->errorInfo);
        }
    }

    public function fetchStatsDataAndStore()
    {

        try {
            $stat = Stats::on('mysql_dse_live')->get();
            foreach ($stat as $ix) {

                $now = Carbon::now();
                $TRD_DATE_TIME = $ix->trd_date;
                $TRD_DATE_TIME->hour = $now->hour;
                $TRD_DATE_TIME->minute = $now->minute;
                $TRD_DATE_TIME->second = $now->second;
                Stats::firstOrCreate([
                    'STATS_INSTRUMENT_CODE' => $ix->STATS_INSTRUMENT_CODE,
                    'STATS_BUYPRICE' => $ix->STATS_BUYPRICE,
                    'STATS_BUYVOLUME' => $ix->STATS_BUYVOLUME,
                    'STATS_BUYDVPVOLUME' => $ix->STATS_BUYDVPVOLUME,
                    'STATS_SELLPRICE' => $ix->STATS_SELLPRICE,
                    'STATS_SELLVOLUME' => $ix->STATS_SELLVOLUME,
                    'STATS_SELLDVPVOLUME' => $ix->STATS_SELLDVPVOLUME,
                    'trd_date' => $TRD_DATE_TIME
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "STATS::";
            var_dump($e->errorInfo);
        }
    }

    public function fetchMkistatDataAndStore()
    {

        try {
            $mkstat = Mkistat::on('mysql_dse_live')->get();

            foreach ($mkstat as $ix) {

                Mkistat::firstOrCreate([
                    'MKISTAT_INSTRUMENT_CODE' => $ix->MKISTAT_INSTRUMENT_CODE,
                    'MKISTAT_INSTRUMENT_NUMBER' => $ix->MKISTAT_INSTRUMENT_NUMBER,
                    'MKISTAT_QUOTE_BASES' => $ix->MKISTAT_QUOTE_BASES,
                    'MKISTAT_OPEN_PRICE' => $ix->MKISTAT_OPEN_PRICE,
                    'MKISTAT_PUB_LAST_TRADED_PRICE' => $ix->MKISTAT_PUB_LAST_TRADED_PRICE,
                    'MKISTAT_SPOT_LAST_TRADED_PRICE' => $ix->MKISTAT_SPOT_LAST_TRADED_PRICE,
                    'MKISTAT_HIGH_PRICE' => $ix->MKISTAT_HIGH_PRICE,
                    'MKISTAT_LOW_PRICE' => $ix->MKISTAT_LOW_PRICE,
                    'MKISTAT_CLOSE_PRICE' => $ix->MKISTAT_CLOSE_PRICE,
                    'MKISTAT_YDAY_CLOSE_PRICE' => $ix->MKISTAT_YDAY_CLOSE_PRICE,
                    'MKISTAT_TOTAL_TRADES' => $ix->MKISTAT_TOTAL_TRADES,
                    'MKISTAT_TOTAL_VOLUME' => $ix->MKISTAT_TOTAL_VOLUME,
                    'MKISTAT_TOTAL_VALUE' => $ix->MKISTAT_TOTAL_VALUE,
                    'MKISTAT_PUBLIC_TOTAL_TRADES' => $ix->MKISTAT_PUBLIC_TOTAL_TRADES,
                    'MKISTAT_PUBLIC_TOTAL_VOLUME' => $ix->MKISTAT_PUBLIC_TOTAL_VOLUME,
                    'MKISTAT_PUBLIC_TOTAL_VALUE' => $ix->MKISTAT_PUBLIC_TOTAL_VALUE,
                    'MKISTAT_SPOT_TOTAL_TRADES' => $ix->MKISTAT_SPOT_TOTAL_TRADES,
                    'MKISTAT_SPOT_TOTAL_VOLUME' => $ix->MKISTAT_SPOT_TOTAL_VOLUME,
                    'MKISTAT_SPOT_TOTAL_VALUE' => $ix->MKISTAT_SPOT_TOTAL_VALUE,
                    'MKISTAT_LM_DATE_TIME' => $ix->MKISTAT_LM_DATE_TIME
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "MKISTAT::";
            var_dump($e->errorInfo);
        }
    }

    public function fetchMANDataAndStore()
    {

        try {
            $man = Man::on('mysql_dse_live')->get();
            foreach ($man as $ix) {
                Man::firstOrCreate([
                    'MAN_ANNOUNCEMENT_DATE_TIME' => $ix->MAN_ANNOUNCEMENT_DATE_TIME,
                    'MAN_ANNOUNCEMENT_PREFIX' => $ix->MAN_ANNOUNCEMENT_PREFIX,
                    'MAN_ANNOUNCEMENT' => $ix->MAN_ANNOUNCEMENT,
                    'MAN_EXPIRY_DATE' => $ix->MAN_EXPIRY_DATE
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "MAN::";
            var_dump($e->errorInfo);
        }
    }

    public function fetchIDXDataAndStore()
    {

        try {
            $idx = Idx::on('mysql_dse_live')->get();
            foreach ($idx as $ix) {
                Idx::firstOrCreate([
                    'IDX_INDEX_ID' => $ix->IDX_INDEX_ID,
                    'IDX_DATE_TIME' => $ix->IDX_DATE_TIME,
                    'IDX_CAPITAL_VALUE' => $ix->IDX_CAPITAL_VALUE,
                    'IDX_DEVIATION' => $ix->IDX_CAPITAL_VALUE,
                    'lDX_PERCENTAGE_DEVIATION' => $ix->IDX_CAPITAL_VALUE,
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "IDX::";
            var_dump($e->errorInfo);
        }
    }
}