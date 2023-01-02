<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use App\Models\PriceEarning;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class PriceEarningController extends Controller
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
        $priceEarnings = PriceEarning::query();
        // Filtering between dates
        if ($request->has('from_date') && $request->has('to_date')) {
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $priceEarnings->whereBetween('data_updated_at_date', [$from_date, $to_date]);
        }
        // Sorting
        if ($request->has('_sort') && $request->has('_order')) {
            if ($request->input('_sort') == "company") {
                $priceEarnings->orderBy(Company::select('code')->whereColumn('companies.id', 'price_earnings.company_id'), $request->input('_order'));
            } else {
                $priceEarnings->orderBy($request->input('_sort'), $request->input('_order'));
            }
        }
        // Searching
        if ($request->has('q')) {
            $keyword = make_keyword($request->input('q'));
            $keyword = '%' . $keyword . '%';
            $searchable = ['id', 'company', 'close_price', 'ycp', 'pe_1', 'pe_2', 'pe_3', 'pe_4', 'pe_5', 'pe_6', 'data_updated_at_date', 'data_updated_at_time'];
            $priceEarnings->where(function ($query) use ($keyword, $searchable) {
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
                        $priceEarnings->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $priceEarnings->where($filter, 'like', $keyword);
                    }
                    break;
                case '=':
                    $keyword = make_number_keyword($filterValue);
                    $priceEarnings->where($filter, '=', $keyword);
                    break;
                case '!=':
                    $keyword = make_number_keyword($filterValue);
                    $priceEarnings->where($filter, '!=', $keyword);
                    break;
                case '>':
                    $keyword = make_number_keyword($filterValue);
                    $priceEarnings->where($filter, '>', $keyword);
                    break;
                case '<':
                    $keyword = make_number_keyword($filterValue);
                    $priceEarnings->where($filter, '<', $keyword);
                    break;
                case '>=':
                    $keyword = make_number_keyword($filterValue);
                    $priceEarnings->where($filter, '>=', $keyword);
                    break;
                case '<=':
                    $keyword = make_number_keyword($filterValue);
                    $priceEarnings->where($filter, '<=', $keyword);
                    break;
                case 'equals':
                    $keyword = make_keyword($filterValue);
                    if ($filter == "company") {
                        $priceEarnings->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $priceEarnings->where($filter, '=', $keyword);
                    }
                    break;
                case 'startsWith':
                    $keyword = make_keyword($filterValue);
                    $keyword = $keyword . '%';
                    if ($filter == "company") {
                        $priceEarnings->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $priceEarnings->where($filter, 'like', $keyword);
                    }
                    break;
                case 'endsWith':
                    $keyword = make_keyword($filterValue);
                    $keyword = '%' . $keyword;
                    if ($filter == "company") {
                        $priceEarnings->whereHas($filter, function (Builder $query) use ($keyword) {
                            $query->where('code', 'like', $keyword);
                        });
                    } else {
                        $priceEarnings->where($filter, 'like', $keyword);
                    }
                    break;
                case 'isEmpty':
                    if ($filter == "company") {
                        $priceEarnings->whereHas($filter, function (Builder $query) {
                            $query->where('code', '');
                        });
                    } else {
                        $priceEarnings->where($filter, '');
                    }
                    break;
                case 'isNotEmpty':
                    if ($filter == "company") {
                        $priceEarnings->whereHas($filter, function (Builder $query) {
                            $query->where('code', '!=', '');
                        });
                    } else {
                        $priceEarnings->where($filter, '!=', '');
                    }
                    break;
                case 'isAnyOf':
                    $keywords = explode(",", $filterValue);
                    $priceEarnings->where(function ($query) use ($filter, $keywords) {
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
        return $priceEarnings->paginate($limit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceEarning  $priceEarning
     * @return \Illuminate\Http\Response
     */
    public function show(PriceEarning $priceEarning)
    {
        return $priceEarning;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceEarning  $priceEarning
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceEarning $priceEarning)
    {
        $priceEarning->delete();

        return response()->json(null, 204);
    }
}
