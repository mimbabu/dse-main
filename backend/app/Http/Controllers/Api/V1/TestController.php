<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Traits\DataScrapeTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\PriceEarning;
use App\Models\Mkistat;
use App\Models\CircuitBreak;
use App\Http\Traits\DomParserTrait;
use App\Http\Traits\CircuitBreakerTrait;
use Egulias\EmailValidator\Parser\DomainPart;

class TestController extends Controller
{
    use DomParserTrait, DataScrapeTrait;

    public function index()
    {

        $url = "https://dsebd.org/latest_PE.php";
        $className = 'table table-bordered background-white  shares-table fixedHeader';
        $table =  $this->parseTable($url, $className, [
            'tradeCode' => '',
            'cp' => 0, 'ycp' => 0, 'pe' => '', 'pe1' => '', 'pe2' => '', 'pe3' => '',
            'pe4' => '', 'pe5' => '', 'pe6' => ''
        ]);



        if (count($table) == 0) {
            echo 'try again';
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
                $price_earning->close_price = $cp;
                $price_earning->ycp = $ycp;
                $price_earning->pe_1 = $row['pe'];
                $price_earning->pe_2 = $row['pe2'];
                $price_earning->pe_3 = $row['pe3'];
                $price_earning->pe_4 = $row['pe4'];
                $price_earning->pe_5 = $row['pe5'];
                $price_earning->pe_6 = $row['pe6'];
                $price_earning->data_updated_at_date = $data_updated_at_date;
                $price_earning->data_updated_at_time = $data_updated_at_time;

                // if ($company->id == 82) {
                //     print_r($price_earning->getAttributes());
                //     print_r(gettype($price_earning->getAttribute('close_price')));
                // }
                $price_earning->save();
            }
        }
    }



    public function foo()
    {
        echo "<pre>";
        $mkstat = Mkistat::on('mysql_dse_live')->get();
        foreach ($mkstat as $mkst)
            print_r($mkst->getAttributes());
    }


    public function circuitfoo()
    {

        echo "<pre>";


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

    public function get_company_details($companyCode)
    {
        $url = 'https://dsebd.org/displayCompany.php?name=' . $companyCode;


        $data = file_get_contents($url);


        $id = 'table table-bordered background-white';
        $table =  $this->parseCompanyDetail($url, $id);

        dd($table);

        // get company from database company table by company code

        // update company detail for market_capital_mn, authorized_capital_mn
        // paidup_capital_mn, total_outstanding_share_mn, category fields
    }

    public function company($companyCode)
    {
        $url = "https://dsebd.org/displayCompany.php?name={$companyCode}";
        $companyData = $this->scrapeCompany($url);
        // $company = Company::where("code", $companyCode)->first();
        // if (!$company) {
        //     $company = new Company();
        // }
        // $company->name = $companyData["name"];
        // // other filds
        // $company->market_capital_mn = $companyData["market_capital_mn"];
        // $company->authorized_capital_mn = $companyData["authorized_capital_mn"];
        // $company->paidup_capital_mn = $companyData["paidup_capital_mn"];
        // $company->total_outstanding_share_mn = $companyData["total_outstanding_share_mn"];
        // $company->category = $companyData["category"];



        // // $company->category = $companyData[7];

        // $company->save();
        return $company;
    }
}