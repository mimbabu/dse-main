<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\LatestSharePrice;

trait StoreLatestSharePriceTrait
{
    use DataScrapeTrait;
    /**
     * Store Latest Share Price in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeLatestSharePrice()
    {
        $url = 'https://dsebd.org/latest_share_price_scroll_by_value.php';
        $table = $this->scrapeTableWithTime($url, '.shares-table', '.topBodyHead', 33);

        foreach ($table as $row) {
            array_shift($row);
            $temp_row = array();
            foreach ($row as $value) {
                if ($value == "--") {
                    $value = "0";
                }
                array_push($temp_row, str_replace(',', '', $value));
            }
            $row = $temp_row;
            $data_updated_at = Carbon::parse($row[10], 'Asia/Dhaka');
            $data_updated_at_date = $data_updated_at->toDateString();
            $data_updated_at_time = $data_updated_at->toTimeString();
            $company = Company::where('code', $row[0])->first();
            if (!$company) {
                $company = new Company;
                $company->code = $row[0];
                $company->save();
            }
            $available = LatestSharePrice::where('company_id', $company->id)
                ->where('data_updated_at_date', $data_updated_at_date)
                ->where('data_updated_at_time', $data_updated_at_time)
                ->first();
            if (!$available) {
                LatestSharePrice::create([
                    'company_id' => $company->id,
                    'ltp' => $row[1],
                    'high' => $row[2],
                    'low' => $row[3],
                    'close_price' => $row[4],
                    'ycp' => $row[5],
                    'change' => $row[6],
                    'trade' => $row[7],
                    'value_mn' => $row[8],
                    'volume' => $row[9],
                    'data_updated_at_date' => $data_updated_at_date,
                    'data_updated_at_time' => $data_updated_at_time
                ]);
            }
        }
    }
}