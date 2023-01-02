<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\LatestSharePrice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PriceEarning;
use Illuminate\Database\Eloquent\Builder;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // limit the number of records to be returned (default is 100)
        $limit = $request->input('_limit', 100);
        $latestSharePrices = LatestSharePrice::query();
        $peFields = ["pe_1", "pe_2", "pe_3", "pe_4", "pe_5", "pe_6"];
        // Filtering between dates
        if ($request->has('from_date') && $request->has('to_date')) {
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $latestSharePrices->whereBetween('data_updated_at_date', [$from_date, $to_date]);
        }
        // Sorting
        if ($request->has('_sort') && $request->has('_order')) {
            if ($request->input('_sort') == "company") {
                $latestSharePrices->orderBy(Company::select('code')->whereColumn('companies.id', 'latest_share_prices.company_id'), $request->input('_order'));
            } elseif (in_array($request->input('_sort'), $peFields)) {
                $latestSharePrices->map(function ($item) use ($request) {
                    $item->company()->orderBy(PriceEarning::select('pe_1')->whereColumn('price_earnings.id', 'companies.company_id'), $request->input('_order'));
                    return $item;
                });
                // return response()->json(['error' => 'Invalid sort field'], 400);
            } else {
                $latestSharePrices->orderBy($request->input('_sort'), $request->input('_order'));
            }
        }
        // Searching
        if ($request->has('q')) {
            $keyword = make_keyword($request->input('q'));
            $keyword = '%' . $keyword . '%';
            $searchable = ['id', 'company', 'ltp', 'high', 'low', 'close_price', 'ycp', 'change', 'trade', 'value_mn', 'volume', 'data_updated_at_date', 'data_updated_at_time'];
            $latestSharePrices->where(function ($query) use ($keyword, $searchable) {
                foreach ($searchable as $column) {
                    if ($column == "company") {
                        $query->orWhereHas($column, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $query->orWhere($column, 'like', $keyword);
                    }
                }
            });
        }
        // Filtering
        if (
            $request->has('_filter') &&
            $request->has('_filterOperatorValue') &&
            $request->has('_filterValue')
        ) {
            $filter = $request->input('_filter');
            $filterValue = $request->input('_filterValue');
            $filterOperatorValue = $request->input('_filterOperatorValue');

            switch ($filterOperatorValue) {
                case 'contains':
                    $keyword = make_keyword($filterValue);
                    $keyword = '%' . $keyword . '%';
                    if ($filter == "company") {
                        $latestSharePrices->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $latestSharePrices->where($filter, 'like', $keyword);
                    }
                    break;
                case '=':
                    $keyword = make_number_keyword($filterValue);
                    $latestSharePrices->where($filter, '=', $keyword);
                    break;
                case '!=':
                    $keyword = make_number_keyword($filterValue);
                    $latestSharePrices->where($filter, '!=', $keyword);
                    break;
                case '>':
                    $keyword = make_number_keyword($filterValue);
                    $latestSharePrices->where($filter, '>', $keyword);
                    break;
                case '<':
                    $keyword = make_number_keyword($filterValue);
                    $latestSharePrices->where($filter, '<', $keyword);
                    break;
                case '>=':
                    $keyword = make_number_keyword($filterValue);
                    $latestSharePrices->where($filter, '>=', $keyword);
                    break;
                case '<=':
                    $keyword = make_number_keyword($filterValue);
                    $latestSharePrices->where($filter, '<=', $keyword);
                    break;
                case 'equals':
                    $keyword = make_keyword($filterValue);
                    if ($filter == "company") {
                        $latestSharePrices->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $latestSharePrices->where($filter, '=', $keyword);
                    }
                    break;
                case 'startsWith':
                    $keyword = make_keyword($filterValue);
                    $keyword = $keyword . '%';
                    if ($filter == "company") {
                        $latestSharePrices->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $latestSharePrices->where($filter, 'like', $keyword);
                    }
                    break;
                case 'endsWith':
                    $keyword = make_keyword($filterValue);
                    $keyword = '%' . $keyword;
                    if ($filter == "company") {
                        $latestSharePrices->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $latestSharePrices->where($filter, 'like', $keyword);
                    }
                    break;
                case 'isEmpty':
                    if ($filter == "company") {
                        $latestSharePrices->whereHas($filter, function (Builder $query) {
                            $query->where('code', '');
                        });
                    } else {
                        $latestSharePrices->where($filter, '');
                    }
                    break;
                case 'isNotEmpty':
                    if ($filter == "company") {
                        $latestSharePrices->whereHas($filter, function (Builder $query) {
                            $query->where('code', '!=', '');
                        });
                    } else {
                        $latestSharePrices->where($filter, '!=', '');
                    }
                    break;
                case 'isAnyOf':
                    $keywords = explode(",", $filterValue);
                    $latestSharePrices->where(function ($query) use ($filter, $keywords) {
                        foreach ($keywords as $keyword) {
                            $query->orWhere($filter, 'like', $keyword);
                        }
                    });
                    break;
                default:
                    break;
            }
        }
        // Load Price Earning
        $data = $latestSharePrices->paginate($limit);
        foreach ($data as $item) {
            $item->load(['company.priceEarnings' => function ($query) use ($item) {
                $query->where('data_updated_at_date', $item->data_updated_at_date)->where('data_updated_at_time', $item->data_updated_at_time);
            }]);
        }
        // if (in_array($request->input('_sort'), $peFields)) {
        //     $data;
        // } else {
        // }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lsp = LatestSharePrice::find($id);
        $lsp->load(['company.priceEarnings' => function ($query) use ($lsp) {
            $query->where('data_updated_at_date', $lsp->data_updated_at_date)->where('data_updated_at_time', $lsp->data_updated_at_time);
        }]);
        if ($lsp) {
            return $lsp;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
