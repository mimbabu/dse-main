<?php

namespace App\Http\Traits;

use DOMDocument;
use Exception;

trait CircuitBreakerTrait
{
    //'table table-bordered background-white  shares-table fixedHeader'
    public function circuitfoo()
    {



        $url = "https://dsebd.org/cbul.php";
        $className = 'table table-bordered background-white text-center';
        $table =  $this->parseTable($url, $className, [
            'tradeCode' => '', 'breaker' => 0, 'tickSize' => 0,
            'openAdjPrice' => 0, 'floorPrice' => '', 'lowerLimit' => '', 'upperLimit' => '',
            'floorPriceBlockMarket' => ''
        ]);




        if (count($table) == 0) {
            echo 'try again';
            return;
        }


        // dd($table);
        foreach ($table  as $row) {


            // $ycp = (float)$row['ycp'];
            // echo gettype((float)$ycp);
            // echo "<br>";
            $mytime = Carbon::now();
            $data_updated_at_date = $mytime->toDateString();
            $data_updated_at_time = $mytime->toTimeString();
            // dd($mytime);
            $company = Company::where('code', $row['tradeCode'])->first();
            if (!$company) {
                $company = new Company;
                $company->code = $row['tradeCode'];
                $company->save();
            }

            $available = CircuitBreak::where('tradecode', $company->id)
                ->where('updated_at', $mytime)
                ->first();

            // $available = CircuitBreak::where('tradecode', $company->id)
            //     ->where('data_updated_at_date', $data_updated_at_date)
            //     ->where('data_updated_at_time', $data_updated_at_time)
            //     ->first();
            // $available = PriceEarning::where('company_id', $company->id)
            //     ->where('data_updated_at_date', $data_updated_at_date)
            //     ->where('data_updated_at_time', $data_updated_at_time)
            //     ->first();

            if (!$available) {
                $floorPrice = (int)str_replace('-', 0, $row['floorPrice']);
                $floorPriceBlockMarket = (int)str_replace('-', 0, $row['floorPriceBlockMarket']);
                $circuitBreak = new CircuitBreak();
                $circuitBreak->tradeCode = 0; //$row['tradeCode'] ? $row['tradeCode'] : 0;
                $circuitBreak->breaker =  (float)$row['breaker'];
                $circuitBreak->tickSize =  (float)$row['tickSize'];
                $circuitBreak->openAdjPrice = (float)$row['openAdjPrice'];
                $circuitBreak->floorPrice =  $floorPrice;
                $circuitBreak->lowerLimit =  (float)$row['lowerLimit'];
                $circuitBreak->upperLimit =  (float)$row['upperLimit'];
                $circuitBreak->floorPriceBlockMarket =  (float)$row['floorPriceBlockMarket'];
                // dump($row);
                // dd("<br>");
                // $circuitBreak->volume =  $row['volume'] ? (int)$row['volume'] : 0;



                // if (empty($circuitBreak['volume'])) {
                //     $circuitBreak['volume'] = '';
                // }

                $circuitBreak->save();
            }
        }
    }
}