<?php

namespace App\Http\Traits;

use App\Models\Idx;
use App\Models\Man;
use App\Models\Trd;
use App\Models\Stats;
use App\Models\Mkistat;


trait DseLiveData
{

    public function fetchTrdDataAndStore()
    {

        try {
            $trd = Trd::on('mysql_dse_live')->get();
            foreach ($trd as $ix) {

                Trd::where("TRD_LM_DATE_TIME", date("Y-m-d"))->delete();

                Trd::firstOrCreate([
                    'TRD_SNO' => validate_data($ix->TRD_SNO),
                    'TRD_TOTAL_TRADES' => validate_data($ix->TRD_TOTAL_TRADES),
                    'TRD_TOTAL_VOLUME' => validate_data($ix->TRD_TOTAL_VOLUME),
                    'TRD_TOTAL_VALUE' => validate_data($ix->TRD_TOTAL_VALUE),
                    'TRD_LM_DATE_TIME' => validate_data($ix->TRD_LM_DATE_TIME)
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "TRD::";
            var_dump($e->errorInfo);
        }
    }

    public function fetchStatsDataAndStore()
    {

        $stat = Stats::on('mysql_dse_live')->get();
        try {
            foreach ($stat as $ix) {
                Stats::firstOrCreate([
                    'STATS_INSTRUMENT_CODE' => $ix->STATS_INSTRUMENT_CODE,
                    'STATS_BUYPRICE' => $ix->STATS_BUYPRICE,
                    'STATS_BUYVOLUME' => $ix->STATS_BUYVOLUME,
                    'STATS_BUYDVPVOLUME' => $ix->STATS_BUYDVPVOLUME,
                    'STATS_SELLPRICE' => $ix->STATS_SELLPRICE,
                    'STATS_SELLVOLUME' => $ix->STATS_SELLVOLUME,
                    'STATS_SELLDVPVOLUME' => $ix->STATS_SELLDVPVOLUME,
                    'trd_date' => $ix->trd_date
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            print "STATS::";
            var_dump($e->errorInfo);
        }
    }

    public function fetchMkistatDataAndStore()
    {
        printf("%s", "mkistat storing...");
        try {
            $mkstat = Mkistat::on('mysql_dse_live')->get();
            foreach ($mkstat as $ix) {
                $mkistatExist = Mkistat::where("MKISTAT_INSTRUMENT_CODE", $ix->MKISTAT_INSTRUMENT_CODE)->where("MKISTAT_LM_DATE_TIME", $ix->MKISTAT_LM_DATE_TIME)->first();
                if (!$mkistatExist) {
                    $mkistat = new Mkistat();
                    $mkistat->MKISTAT_INSTRUMENT_CODE = $ix->MKISTAT_INSTRUMENT_CODE;
                    $mkistat->MKISTAT_INSTRUMENT_NUMBER = intval($ix->MKISTAT_INSTRUMENT_NUMBER);
                    $mkistat->MKISTAT_QUOTE_BASES = $ix->MKISTAT_QUOTE_BASES;
                    $mkistat->MKISTAT_OPEN_PRICE = floatval($ix->MKISTAT_OPEN_PRICE);
                    $mkistat->MKISTAT_PUB_LAST_TRADED_PRICE = floatval($ix->MKISTAT_PUB_LAST_TRADED_PRICE);
                    $mkistat->MKISTAT_SPOT_LAST_TRADED_PRICE = floatval($ix->MKISTAT_SPOT_LAST_TRADED_PRICE);
                    $mkistat->MKISTAT_HIGH_PRICE = floatval($ix->MKISTAT_HIGH_PRICE);
                    $mkistat->MKISTAT_LOW_PRICE = floatval($ix->MKISTAT_LOW_PRICE);
                    $mkistat->MKISTAT_CLOSE_PRICE = floatval($ix->MKISTAT_CLOSE_PRICE);
                    $mkistat->MKISTAT_YDAY_CLOSE_PRICE = floatval($ix->MKISTAT_YDAY_CLOSE_PRICE);
                    $mkistat->MKISTAT_TOTAL_TRADES = intval($ix->MKISTAT_TOTAL_TRADES);
                    $mkistat->MKISTAT_TOTAL_VOLUME = intval($ix->MKISTAT_PUBLIC_TOTAL_VOLUME);
                    $mkistat->MKISTAT_TOTAL_VALUE = floatval($ix->MKISTAT_TOTAL_VALUE);
                    $mkistat->MKISTAT_PUBLIC_TOTAL_TRADES =  $ix->MKISTAT_PUBLIC_TOTAL_TRADES;
                    $mkistat->MKISTAT_SPOT_TOTAL_TRADES = intval($ix->MKISTAT_SPOT_TOTAL_TRADES);
                    $mkistat->MKISTAT_SPOT_TOTAL_VOLUME =  intval($ix->MKISTAT_SPOT_TOTAL_VOLUME);
                    $mkistat->MKISTAT_SPOT_TOTAL_VALUE = floatval($ix->MKISTAT_SPOT_TOTAL_VALUE);
                    $mkistat->MKISTAT_LM_DATE_TIME = $ix->MKISTAT_LM_DATE_TIME;
                    $mkistat->save();
                }

                // dd($mkistat);
                // Mkistat::firstOrCreate([
                //     'MKISTAT_INSTRUMENT_CODE' => $ix->MKISTAT_INSTRUMENT_CODE,
                //     'MKISTAT_INSTRUMENT_NUMBER' =>  intval($ix->MKISTAT_INSTRUMENT_NUMBER),
                //     'MKISTAT_QUOTE_BASES' => $ix->MKISTAT_QUOTE_BASES,
                //     'MKISTAT_OPEN_PRICE' => floatval($ix->MKISTAT_OPEN_PRICE),
                //     'MKISTAT_PUB_LAST_TRADED_PRICE' => floatval($ix->MKISTAT_PUB_LAST_TRADED_PRICE),
                //     'MKISTAT_SPOT_LAST_TRADED_PRICE' => floatval($ix->MKISTAT_SPOT_LAST_TRADED_PRICE),
                //     'MKISTAT_HIGH_PRICE' => floatval($ix->MKISTAT_HIGH_PRICE),
                //     'MKISTAT_LOW_PRICE' => floatval($ix->MKISTAT_LOW_PRICE),
                //     'MKISTAT_CLOSE_PRICE' => floatval($ix->MKISTAT_CLOSE_PRICE),
                //     'MKISTAT_YDAY_CLOSE_PRICE' => floatval($ix->MKISTAT_YDAY_CLOSE_PRICE),
                //     'MKISTAT_TOTAL_TRADES' =>  intval($ix->MKISTAT_TOTAL_TRADES),
                //     'MKISTAT_TOTAL_VOLUME' =>  intval($ix->MKISTAT_TOTAL_VOLUME),
                //     'MKISTAT_TOTAL_VALUE' => floatval($ix->MKISTAT_TOTAL_VALUE),
                //     'MKISTAT_PUBLIC_TOTAL_TRADES' =>  intval($ix->MKISTAT_PUBLIC_TOTAL_TRADES),
                //     'MKISTAT_PUBLIC_TOTAL_VOLUME' =>  intval($ix->MKISTAT_PUBLIC_TOTAL_VOLUME),
                //     'MKISTAT_PUBLIC_TOTAL_VALUE' => floatval($ix->MKISTAT_PUBLIC_TOTAL_VALUE),
                //     'MKISTAT_SPOT_TOTAL_TRADES' =>  intval($ix->MKISTAT_SPOT_TOTAL_TRADES),
                //     'MKISTAT_SPOT_TOTAL_VOLUME' =>  intval($ix->MKISTAT_SPOT_TOTAL_VOLUME),
                //     'MKISTAT_SPOT_TOTAL_VALUE' => floatval($ix->MKISTAT_SPOT_TOTAL_VALUE),
                //     'MKISTAT_LM_DATE_TIME' => $ix->MKISTAT_LM_DATE_TIME
                // ]);
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