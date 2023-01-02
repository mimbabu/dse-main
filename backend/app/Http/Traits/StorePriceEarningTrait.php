<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\PriceEarning;

trait StorePriceEarningTrait
{
    use DataScrapeTrait;
    use DomParserTrait;
    /**
     * Store Latest Share Price in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storePriceEarning()
    {





        $url = "https://dsebd.org/latest_PE.php";
        $className = 'table table-bordered background-white  shares-table fixedHeader';
        $table =  $this->parseTable($url, $className, [
            'tradeCode' => '', 'cp' => (float)0, 'ycp' => (float)0, 'pe' => (float)0,
            'pe2' => (float)0, 'pe3' => (float)0, 'pe4' => (float)0, 'pe5' => (float)0, 'pe6' => (float)0
        ]);





        if (count($table) == 0) {
            echo 'tri again ';
            return;
        }

        foreach ($table  as $row) {


            $cp = (gettype($row['cp']) == 'string') ? ((float)$row['cp'] * 1.0) : $row['cp'] * 1.0;
            $ycp = (gettype($row['ycp']) == 'string') ? ((float)$row['ycp'] * 1.0) : $row['ycp'] * 1.0;
            // echo gettype((float)$ycp);
            // echo "<br>";
            $mytime = Carbon::now();
            $data_updated_at_date = $mytime->toDateString();
            $data_updated_at_time = $mytime->toTimeString();
            $company = Company::where('code', $row['tradeCode'])->first();
            if (!$company) {
                $company = new Company;
                $company->code = $row['tradeCode'];
                $company->save();
            }
            $available = PriceEarning::where('company_id', $company->id)
                ->where('data_updated_at_date', $data_updated_at_date)
                ->where('data_updated_at_time', $data_updated_at_time)
                ->first();

            if (!$available) {

                $price_earning = new PriceEarning();
                $price_earning->company_id = $company->id;
                // $price_earning->close_price = $cp;
                // $price_earning->ycp = (float)$ycp;
                $price_earning->pe_1 = (float)$row['pe'];
                $price_earning->pe_2 = (float)$row['pe2'];
                $price_earning->pe_3 = (float)$row['pe3'];
                $price_earning->pe_4 = (float)$row['pe4'];
                $price_earning->pe_5 = (float)$row['pe5'];
                $price_earning->pe_6 = (float)$row['pe6'];
                $price_earning->data_updated_at_date = $data_updated_at_date;
                $price_earning->data_updated_at_time = $data_updated_at_time;
                // dd($price_earning);
                print_r("here before insert price-earnings");
                $price_earning->save();
            }
        }
    }
}