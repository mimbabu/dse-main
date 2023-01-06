<?php

namespace App\Http\Traits;

use App\Models\Company;
use Goutte\Client;

trait DataScrapeTrait
{
    /**
     * Scrape table from a url by css selector
     *
     * @param string $url
     * @param string $selector
     * @return array $data
     */
    public function scrapeTable(string $url, string $selector)
    {
        $results = array();
        $client = new Client();
        $page = $client->request('GET', $url);
        $page->filter($selector)->filter('tr')->each(function ($tr, $i) use (&$results) {
            $row = [];
            $tr->filter('td, th')->each(function ($td, $i) use (&$row) {
                array_push($row, $td->text());
            });
            array_push($results, $row);
        });
        $data = $results;
        return $data;
    }

    /**
     * Scrape text from a url by css selector
     *
     * @param string $url
     * @param string $selector
     * @return array $data
     */
    public function scrapeText(string $url, string $selector)
    {
        $client = new Client();
        $page = $client->request('GET', $url);
        $data = $page->filter($selector)->text();
        return $data;
    }

    /**
     * Scrape table and time from a url by css selector
     *
     * @param string $url
     * @param string $tableSelector
     * @param string $dateSelector
     * @param int $offset
     * @return array $data
     */
    public function scrapeTableWithTime($url, $tableSelector, $dateSelector, $offset)
    {
        try {

            $results = array();
            $data = array();
            $client = new Client();
            $page = $client->request('GET', $url);
            file_put_contents('document.html', file_get_contents($url));
            $page->filter($tableSelector)->filter('tr')->each(function ($tr, $i) use (&$results) {

                $row = [];
                $tr->filter('td, th')->each(function ($td, $i) use (&$row) {
                    array_push($row, $td->text());
                });
                array_push($results, $row);
            });
            $data_updated_at_text = $page->filter($dateSelector)->text();
            $data_updated_at = substr($data_updated_at_text, $offset);

            foreach ($results as $i => $row) {
                if ($i == 0) {
                    array_push($row, "Data Updated At");
                    continue;
                }
                array_push($row, $data_updated_at);
                array_push($data, $row);
            }
            return $data;
        } catch (\Throwable $th) {
            return null;
        }
    }
    /**
     * Scrape company data from the url
     *
     * @param string $url
     * @return array $data
     */
    public function scrapeCompany(string $url)
    {

        $table = array();
        $client = new Client();
        // $companies =  Company::select(['id', 'code'])->get();

        // foreach ($companies as $com) {
        // print_r($com->code);
        echo " $url\n";

        $page = $client->request('GET', $url);
        $companyName = $page->filter('.row .topBodyHead:first-child')->text();
        $companyName = substr($companyName, 14);


        $company = [];

        $page->filter('#company')->filter('tr')->each(function ($tr, $i) use (&$table) {
            $row = [];
            $tr->filter('td, th')->each(function ($td, $i) use (&$row) {
                echo $td->text() . "<br/>";
                array_push($row, $td->text());
            });
            array_push($table, $row);
        });
        // Market Capitalization (mn)
        // $company["market_capitalization_mn"] = str_replace(',', '', $table[7][3]);
        dd($url, $table);

        $ltp = trim(str_replace('-', '', $table[1][1]));

        $opening_price = trim(str_replace('-', '', $table[5][1]));

        if (strlen($ltp) > 0 && strlen($opening_price) > 0) {
            $company["name"] = $companyName;
            $company["market_capital_mn"] = str_replace(',', '', $table[7][3]);


            $company["authorized_capital_mn"] = str_replace(',', '', $table[8][1]);

            $company["paidup_capital_mn"] = str_replace(',', '', $table[9][1]);

            $company["type_of_instrument"] = str_replace(',', '', $table[9][3]);

            $company["face_par_value"] = str_replace(',', '', $table[10][1]);

            $company["total_outstanding_share_mn"] = str_replace(',', '', $table[11][1]);

            $company["cash_dividend"] = str_replace(',', '', $table[14][1]);
            $company["bonus_issued_stock_dividend"] = str_replace(',', '', $table[15][1]);
            if (!empty($table[39][6])) {
                $company["current_pe"] = str_replace(',', '', $table[39][6]);
            } else {
                $company["current_pe"] = 0;
            }

            $company["sector"] = str_replace(',', '', $table[11][3]);



            $company["listing_since"] = $table[60][1];
            $company["category"] = $table[61][1];
            // dd($table[6][1]);
            $company["address"] = str_replace(',', '', $table[79][2]);
            $company["phone"] = str_replace(',', '', $table[81][1]);


            $company["mobile_no"] = str_replace(',', '', $table[86][1]);

            // dd($table[86][1]);

            $company["email"] = $table[83][1];

            $epsStart = 44;
            $epsEnd = 0;
            for ($i = 44; $i <= ($epsStart + 5); $i++) {
                if ($table[$i] != null) {
                    if ($table[$i][0] != "Year") {
                        $company["eps"] = $table[$i][4];
                        $company["nav"] = $table[$i][7];
                        $epsEnd = $i;
                    }
                }
            }
            $dividendStart = ($epsEnd + 5);
            $dividendEnd = 0;
            for ($i = $dividendStart; $i <= ($dividendStart + 5); $i++) {
                if ($table[$i] != null) {
                    if ($table[$i][0] != "Details of Financial Statement") {
                        $company["dividend"] = $table[$i][7];
                        $company["dividend_yield"] = $table[$i][8];
                        $dividendEnd = $i;
                    }
                }
            }

            $shareHoldingPatternStart = $dividendEnd + 6;


            $currentYear = date('y');
            $lastYear = $currentYear - 1;


            while ($table[$shareHoldingPatternStart][0] != "Remarks") {


                if (str_contains($table[$shareHoldingPatternStart][0], $currentYear)) {
                    $company['sponsor_director'] = $table[$shareHoldingPatternStart][2];
                    $company['govt'] = $table[$shareHoldingPatternStart][3];
                    $company['institute'] = $table[$shareHoldingPatternStart][4];
                    $company['foreign'] = $table[$shareHoldingPatternStart][5];
                    $company['public'] = $table[$shareHoldingPatternStart][6];
                    break;
                }
                if (str_contains($table[$shareHoldingPatternStart][0], $lastYear)) {
                    $company['sponsor_director'] = $table[$shareHoldingPatternStart][2];
                    $company['govt'] = $table[$shareHoldingPatternStart][3];
                    $company['institute'] = $table[$shareHoldingPatternStart][4];
                    $company['foreign'] = $table[$shareHoldingPatternStart][5];
                    $company['public'] = $table[$shareHoldingPatternStart][6];
                }
                $shareHoldingPatternStart++;
            }
        }
        // dd($company);

        // $com->market_capital_mn = $company['market_capital_mn'];
        // $com->authorized_capital_mn = $company['market_capital_mn'];
        // $com->paidup_capital_mn = $company['paidup_capital_mn'];
        // $com->total_outstanding_share_mn = $company['total_outstanding_share_mn'];
        // $com->category = $company['category'];


        // $com->save();
        // array_push($results, str_replace(',', '', $table[7][3]));
        // Sector 	authorized_capital_mn, paidup_capital_mn 	total_outstanding_share_mn	category
        // array_push($results, $table[11][3]);
        // Yesterday's Closing Price
        // array_push($results, str_replace(',', '', $table[7][1]));
        // // Authorized Capital
        // array_push($results, str_replace(',', '', $table[9][1]));

        // Paidup Capital
        // array_push($results, str_replace(',', '', $table[10][1]));

        // Total outstanding securities

        // array_push($results, str_replace(',', '', $table[12][1]));

        //  Market Category

        // array_push($results, $table[61][1]);
        // dd($table[61][1]);
        // }
        return $company;
    }
}