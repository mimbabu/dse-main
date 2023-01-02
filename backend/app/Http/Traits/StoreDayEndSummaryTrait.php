<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\DayEndSummary;

trait StoreDayEndSummaryTrait
{
    use DataScrapeTrait;

    /**
     * Store Latest Share Price in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeDayEndSummary($dt)
    {
        $dt_obj = Carbon::parse($dt, 'Asia/Dhaka');
        $dt_obj = $dt_obj->isFriday() ? $dt_obj->addDays(2) : ($dt_obj->isSaturday() ? $dt_obj->addDay() : $dt_obj);
        $dt_obj_str = $dt_obj->toDateString();
        $url = "https://dsebd.org/day_end_archive.php?startDate={$dt_obj_str}&endDate={$dt_obj_str}&inst=All%20Instrument&archive=data";
        $table = $this->scrapeTableWithTime($url, '.shares-table', '.topBodyHead', 37);
        if ($table[0][0] == 'No Day End Data') {
            $next_dt = Carbon::parse($dt)->addDay()->toDateString();
            return $this->storeDayEndSummary($next_dt);
        }
        foreach ($table as $row) {
            array_shift($row);
            $temp_row = array();
            foreach ($row as $value) {
                if ($value == "--" || $value == "-") {
                    $value = "0";
                }
                array_push($temp_row, str_replace(',', '', $value));
            }
            $row = $temp_row;
            $data_updated_at = Carbon::parse($row[0], 'Asia/Dhaka');
            if ($dt_obj->isSameDay($data_updated_at)) {
                $company = Company::where('code', $row[1])->first();
                if (!$company) {
                    $company = new Company;
                    $company->code = $row[1];
                    $company->save();
                }
                $available = DayEndSummary::where('company_id', $company->id)
                    ->where('data_updated_at_date', $data_updated_at->toDateString())
                    ->first();
                if (!$available) {
                    $dayEndSummary = new DayEndSummary();
                    $dayEndSummary->company_id = $company->id;
                    $dayEndSummary->ltp = $row[2];
                    $dayEndSummary->high = $row[3];
                    $dayEndSummary->low = $row[4];
                    $dayEndSummary->open_price = $row[5];
                    $dayEndSummary->close_price = $row[6];
                    $dayEndSummary->ycp = $row[7];
                    $dayEndSummary->trade = $row[8];
                    $dayEndSummary->value_mn = $row[9];
                    $dayEndSummary->volume = $row[10];
                    $dayEndSummary->data_updated_at_date = $data_updated_at->toDateString();
                    $dayEndSummary->save();
                }
            }
        }
    }

    public function storeLatestDayEndSummary()
    {
        $now = Carbon::now('Asia/Dhaka');
        $latest = DayEndSummary::latest('data_updated_at_date')->first();
        $latest_date = !$latest ? $now->subYears(2) : Carbon::parse($latest->data_updated_at_date, 'Asia/Dhaka')->addDay();
        $latest_date = $latest_date->isFriday() ? $latest_date->addDays(2) : ($latest_date->isSaturday() ? $latest_date->addDay() : $latest_date);
        if (!$latest_date->isToday() && !$latest_date->isFuture()) {
            $this->storeDayEndSummary($latest_date->toDateString());
        }
    }
}