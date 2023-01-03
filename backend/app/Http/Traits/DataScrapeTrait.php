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


        $page = $client->request('GET', $url);
        $companyName = $page->filter('.row .topBodyHead:first-child')->text();
        $companyName = substr($companyName, 14);
        // Company Name
        $company["name"] = $companyName;






        $page->filter('#RightBody')->filter('tr')->each(function ($tr, $i) use (&$table) {
            $row = [];
            $tr->filter('td, th')->each(function ($td, $i) use (&$row) {
                array_push($row, $td->text());
            });
            array_push($table, $row);
        });
        // Market Capitalization (mn)
        // $company["market_capitalization_mn"] = str_replace(',', '', $table[7][3]);
        // dd($url, $table);
        $company["market_capital_mn"] = str_replace(',', '', $table[7][3]);


        $company["authorized_capital_mn"] = str_replace(',', '', $table[8][1]);

        $company["paidup_capital_mn"] = str_replace(',', '', $table[9][1]);

        $company["total_outstanding_share_mn"] = str_replace(',', '', $table[11][1]);


        $company["category"] = $table[61][1];

        $company["address"] = str_replace(',', '', $table[79][2]);
        dd($table[81][1]);
        $company["Contact Phone"] = str_replace(',', '', $table[81][1]);

        $company["cell_no"] = str_replace(',', '', $table[86][1]);



        $company["E-mail"] = $table[83][1];
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