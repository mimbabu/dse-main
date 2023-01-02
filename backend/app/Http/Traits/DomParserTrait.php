<?php

namespace App\Http\Traits;

use DOMDocument;
use Exception;

trait DomParserTrait
{
    //'table table-bordered background-white  shares-table fixedHeader'
    public function parseTable($url, $className, $columns)
    {

        libxml_use_internal_errors(true);

        // $url = "https://dsebd.org/latest_PE.php";
        $trades = array();
        try {
            $doc = file_get_contents($url);
            // echo $doc;
            // exit;
            if (empty($doc)) {
                echo "try again";
                return;
            }
            /* a new dom object */
            $dom = new DomDocument;

            /* load the html into the object */
            $dom->loadHTML($doc);

            /* discard white space */
            $dom->preserveWhiteSpace = false;

            /* the table by its tag name */
            $tables = $dom->getElementsByTagName('table');
            $targetTable = null;
            foreach ($tables as $table) {
                if (
                    !empty($table->getAttribute('class'))
                    && $table->getAttribute('class') == $className
                ) {
                    $targetTable = $table;
                }
            }
            $rows = $targetTable->getElementsByTagName('tr');

            /* get all rows from the table */
            for ($i = 0; $i < count($rows); $i++) {

                $row = $rows->item($i)->getElementsByTagName('td');

                if (count($row) > 0) {
                    // print_r($row);

                    /* echo the values */
                    $j = 1;
                    $trade = array();
                    foreach ($columns as $col => $colVal) {
                        if ($j <= count($row)) {
                            $nodeValue = (!empty($row->item($j)->nodeValue)) ? $row->item($j)->nodeValue : $colVal;
                            $trade[$col] = $nodeValue;
                        }

                        $j++;
                    }

                    array_push($trades, $trade);
                }
            }
        } catch (Exception $ex) {
            echo "try again";
        }
        return $trades;
    }

    public function parseCompanyDetail($url, $className)
    {
        libxml_use_internal_errors(true);

        // $url = "https://dsebd.org/latest_PE.php";
        $trades = array();
        try {
            $doc = file_get_contents($url);
            // echo $doc;
            // exit;
            if (empty($doc)) {
                echo "try again";
                return;
            }


            /* a new dom object */
            $dom = new DomDocument;

            /* load the html into the object */
            $dom->loadHTML($doc);

            /* discard white space */
            $dom->preserveWhiteSpace = false;

            // dd($dom);
            /* the table by its tag name */
            $tables = $dom->getElementsByTagName('table');
            $targetTable = null;

            $companyDetail = [
                'authorizedCapital' => 0,
                'marketCapital' => 0,
                'paidUpCapital' => 0,
                'outstandingShare' => 0,
                'category' => ''
            ];
            $filteredTables = [];
            foreach ($tables as $table) {
                if (
                    !empty($table->getAttribute('class'))
                    && $table->getAttribute('class') == $className
                ) {
                    $filteredTables[] = $table;
                }
            };
            foreach ($filteredTables[0]->childNodes as $item) {
                $value = $item->nodeValue;

                if (preg_match('/Market Capitalization/', $value)) {
                    $arr = explode("Market Capitalization", trim($value));
                    $companyDetail['marketCapital'] = trim(str_replace("(mn)", "", $arr[1]));
                }
            }

            foreach ($filteredTables[1]->childNodes as $k => $item) {
                $value = $item->nodeValue;
                if ($k == 1) {
                    $arr = explode("Authorized Capital (mn)", $value);
                    $companyDetail['authorizedCapital'] = (trim(str_replace("Debut Trading Date", "", $arr[1])));
                }
                if ($k == 3) {
                    $arr = explode("Paid-up Capital (mn)", $value);
                    $companyDetail['paidUpCapital'] = (trim(preg_replace("/[a-zA-Z]/", "", $arr[1])));
                }
                if ($k == 7) {
                    // if ($k == 3) {
                    $arr = explode("Total No. of Outstanding Securities", $value);
                    $companyDetail['outstandingShare'] = (trim(preg_replace("/[a-zA-Z]/", "", $arr[1])));
                    // }
                }
            }



            foreach ($filteredTables[9]->childNodes as $k => $item) {
                $value = $item->nodeValue;
                if ($k == 3) {
                    $companyDetail['category'] = (trim(preg_replace("/Market Category/", "", $value)));
                }
            }
        } catch (Exception $ex) {
            echo "try again" . $ex->getMessage();
        }

        return $companyDetail;
    }
}