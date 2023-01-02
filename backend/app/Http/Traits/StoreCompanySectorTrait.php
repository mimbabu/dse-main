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
        $companies = Company::all();
        foreach ($companies as $company) {
            if ($company->sector_id == null) {
                $url = 'https://dsebd.org/displayCompany.php?name=' . $company->code;
                $company_data = $this->scrapeCompany($url);
                $company->name = $company_data['name'];
                $company->market_capital_mn = $company_data['market_capital_mn'];
                $company->authorized_capital_mn = $company_data['authorized_capital_mn'];
                $company->paidup_capital_mn = $company_data['paidup_capital_mn'];
                $company->total_outstanding_share_mn = $company_data['total_outstanding_share_mn'];

                $company->category = $company_data['category'];




                $sector = Sector::where('name', $company_data[2])->first();
                if (!$sector) {
                    $sector = new Sector;
                    $sector->name = $company_data[2];
                    $sector->save();
                }
                $company->sector_id = $sector->id;
                $company->save();
            }
        }
    }
}