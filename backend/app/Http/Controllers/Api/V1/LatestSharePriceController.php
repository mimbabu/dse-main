<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\LatestSharePrice;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class LatestSharePriceController extends Controller
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
        // return the result
        return $latestSharePrices->paginate($limit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LatestSharePrice  $latestSharePrice
     * @return \Illuminate\Http\Response
     */
    public function show(LatestSharePrice $latestSharePrice)
    {
        return $latestSharePrice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LatestSharePrice  $latestSharePrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LatestSharePrice $latestSharePrice)
    {
        $latestSharePrice->update($request->all());

        return $latestSharePrice;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LatestSharePrice  $latestSharePrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(LatestSharePrice $latestSharePrice)
    {
        $latestSharePrice->delete();

        return response()->json(null, 204);
    }
}
