<?php

namespace App\Http\Traits;

use App\Models\Sector;
use App\Models\Company;
use App\Http\Traits\DataScrapeTrait;

trait StoreCompanySectorTrait
{
    use DataScrapeTrait;
    /**
     * Store Latest Share Price in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCompanySector()
    {
        $companies = Company::where('code', 'not like', 'TB%')->orderBy('code', 'asc')->get();
        foreach ($companies as $company) {
            if ($company->sector_id == null) {
                $url = 'https://dsebd.org/displayCompany.php?name=' . $company->code;
                $company_data = $this->scrapeCompany($url);
                if (!empty($company_data['name'])) {
                    $company->name = $company_data['name'];

                    $company->market_capital_mn = $this->filter_data($company_data['market_capital_mn']);

                    $company->authorized_capital_mn = $this->filter_data($company_data['authorized_capital_mn']);


                    $company->paidup_capital_mn = $this->filter_data($company_data['paidup_capital_mn']);
                    $company->type_of_instrument = $company_data['type_of_instrument'];
                    $company->face_par_value = $company_data['face_par_value'];
                    $company->total_outstanding_share_mn = $this->filter_data($company_data['total_outstanding_share_mn']);
                    $company->cash_dividend = $company_data['cash_dividend'];
                    $company->bonus_issued_stock_dividend = $company_data['bonus_issued_stock_dividend'];
                    $company->current_pe = $company_data['current_pe'];
                    // $company->sector = $company_data['sector'];
                    $company->listing_since = $company_data['listing_since'];
                    $company->category = $company_data['category'];
                    $company->address = $company_data['address'];
                    $company->phone = $company_data['phone'];
                    $company->mobile_no = $company_data['mobile_no'];
                    $company->eps = $company_data['eps'];
                    $company->nav = $company_data['nav'];
                    $company->dividend = $company_data['dividend'];
                    $company->dividend_yield = $company_data['dividend_yield'];
                    $company->sponsor_director = $company_data['sponsor_director'];
                    $company->govt = $company_data['govt'];
                    $company->institute = $company_data['institute'];
                    $company->foreign = $company_data['foreign'];
                    $company->public = $company_data['public'];
                    $company->email = $company_data['email'];




                    $sector = Sector::where('name', $company_data['sector'])->first();
                    if (!$sector) {
                        $sector = new Sector;
                        $sector->name = $company_data['sector'];
                        $sector->save();
                    }
                    $company->sector_id = $sector->id;
                    $company->save();
                }
            }
        }
    }

    function filter_data($input)
    {
        $fiterData = trim(str_replace('-', '', $input));
        return (strlen($fiterData) > 0) ? $fiterData : 0;
    }
}