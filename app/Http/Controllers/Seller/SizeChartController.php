<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeChartRequest;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\MeasurementPoint;
use App\Models\SizeChart;
use App\Models\SizeChartDetail;
use App\Services\ProductService;
use Illuminate\Http\Request;

class SizeChartController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        $sort_search = null;
        $sizeChart_tabs = ['All Size Charts'];
        $sizeCharts = SizeChart::where('user_id', auth()->id())
            ->orWhere(function ($query) {
                $query->where('user_id', get_admin()->id)
                    ->where('seller_access', 1);
            });

        if ($request->search != null) {
            $sort_search = $request->search;
            $sizeCharts = $sizeCharts->where('name', 'like', '%' . $request->search . '%');
        }
        $sizeCharts = $sizeCharts->orderBy('created_at', 'desc')->paginate(15);
        return view('seller.product.sizeCharts.index', compact('sizeCharts', 'sizeChart_tabs', 'categories', 'sort_search'));
    }

    public function filter(Request $request)
    {
        $sizeCharts = SizeChart::where('user_id', auth()->id())
            ->orWhere(function ($query) {
                $query->where('user_id', get_admin()->id)
                    ->where('seller_access', 1);
            });

        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $sizeCharts = $sizeCharts->where(function ($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%');
            });
        }

        $sizeCharts = $sizeCharts->orderBy('created_at', 'desc')->paginate(15);

        $view = view(
            'seller.product.sizeCharts.table',
            compact('sizeCharts', 'sort_search')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function search(Request $request)
    {
        $products = $this->productService->sizeChart_products_search($request->except(['_token']));
        $single_select = $request->single_select ?? 0;
        $readonly = $request->readonly ?? 0;
        return view('seller.product.sizeCharts.products_search', compact('products', 'single_select', 'readonly'));
    }

    public function create()
    {
        $measurementPoints = MeasurementPoint::orderBy('created_at', 'asc')->paginate(15);
        $sizeOptions = AttributeValue::selectRaw('id,value')->orderBy('created_at', 'asc')->get();

        return view('seller.product.sizeCharts.create', compact('measurementPoints', 'sizeOptions'));
    }

    public function store(SizeChartRequest $request)
    {
        $size_chart = SizeChart::create($request->except([
            'size_chart_values'
        ]));

        $this->storeSizeChartDetail($size_chart->id, $request->only([
            'measurement_option',
            'measurement_points',
            'size_options',
            'size_chart_values',
        ]));

        flash(translate('Size Chart has been created successfully'))->success();
        return redirect()->route('seller.size-charts.index');
    }

    public function show($id)
    {
        $size_chart = SizeChart::findOrFail($id);

        $measurement_options = json_decode($size_chart->measurement_option);
        $measurement_option_inch = in_array("inch", $measurement_options) ? 1 : 0;
        $measurement_option_cen = in_array("cen", $measurement_options) ? 1 : 0;

        $measurementPoints = MeasurementPoint::whereIn('id', json_decode($size_chart->measurement_points, true))->get();
        $size_options = AttributeValue::selectRaw('id,value')->whereIn('id', json_decode($size_chart->size_options, true))->get();

        $data = array();
        foreach ($size_chart->sizeChartDetails as $sizeChartDetail) {
            $data['inch'][$sizeChartDetail->measurement_point_id][$sizeChartDetail->attribute_value_id] =  $sizeChartDetail->inch_value;
            $data['cen'][$sizeChartDetail->measurement_point_id][$sizeChartDetail->attribute_value_id] =  $sizeChartDetail->cen_value;
        }

        return view('seller.product.sizeCharts.show', compact('measurementPoints', 'size_options', 'measurement_option_inch', 'measurement_option_cen', 'size_chart', 'data'));
    }

    public function edit(SizeChart $size_chart)
    {

        $measurementPoints = MeasurementPoint::orderBy('created_at', 'asc')->get();
        $sizeOptions = AttributeValue::selectRaw('id,value')->orderBy('created_at', 'asc')->get();

        $data = array();
        foreach ($size_chart->sizeChartDetails as $sizeChartDetail) {
            $data['inch'][$sizeChartDetail->measurement_point_id][$sizeChartDetail->attribute_value_id] =  $sizeChartDetail->inch_value;
            $data['cen'][$sizeChartDetail->measurement_point_id][$sizeChartDetail->attribute_value_id] =  $sizeChartDetail->cen_value;
        }

        return view('seller.product.sizeCharts.edit', compact('measurementPoints', 'sizeOptions', 'size_chart', 'data'));
    }

    public function update(SizeChartRequest $request, SizeChart $size_chart)
    {
        $size_chart->update($request->except([
            'size_chart_values'
        ]));
        $size_chart->sizeChartDetails()->delete();

        $this->storeSizeChartDetail($size_chart->id, $request->only([
            'measurement_option',
            'measurement_points',
            'size_options',
            'size_chart_values',
        ]));

        flash(translate('Size Chart has been updated successfully'))->success();
        return redirect()->route('seller.size-charts.index');
    }

    public function destroy($id)
    {
        $size_chart = SizeChart::findOrFail($id);
        $size_chart = SizeChart::where('id', $id)->first();
        if (!is_null($size_chart)) {
            $size_chart->delete();
            $size_chart->sizeChartDetails()->delete();
        }

        return 1;
    }

    public function bulk_size_chart_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $sizeChart_id) {

                $size_chart = SizeChart::find($sizeChart_id);

                if (!$size_chart) {
                    continue;
                }

                if ($size_chart->user_id != auth()->id()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => translate("You cannot delete admin's size chart")
                    ]);
                }

                $size_chart->sizeChartDetails()->delete();
                $size_chart->delete();
            }

            return 1;
        }

        return 0;
    }

    public function get_combination(Request $request)
    {
        $measurement_option_inch = $request->measurement_option_inch;
        $measurement_option_cen = $request->measurement_option_cen;
        $measurementPoints = MeasurementPoint::whereIn('id', $request->measurement_points)->get();
        $size_options = AttributeValue::selectRaw('id,value')->whereIn('id', $request->size_options)->get();

        return view('seller.product.sizeCharts.size_combination', compact('measurementPoints', 'size_options', 'measurement_option_inch', 'measurement_option_cen'));
    }

    public function storeSizeChartDetail($size_chart_id, $request)
    {
        $measurement_options = json_decode($request['measurement_option']);
        $data = array();
        $i = 0;
        foreach (json_decode($request['measurement_points']) as $measurement_point) {
            foreach (json_decode($request['size_options']) as $size_option) {
                $data[$i]['size_chart_id'] = $size_chart_id;
                $data[$i]['measurement_point_id'] = $measurement_point;
                $data[$i]['attribute_value_id'] = $size_option;
                $data[$i]['inch_value'] = in_array("inch", $measurement_options) ? $request['size_chart_values'][$measurement_point][$size_option]['inch'] : null;
                $data[$i]['cen_value'] = in_array("cen", $measurement_options) ? $request['size_chart_values'][$measurement_point][$size_option]['cen'] : null;
                $i += 1;
            }
        }

        SizeChartDetail::insert($data);
    }

    public function updateCategoryOrProducts(Request $request)
    {
        $request->validate([
            'size_chart_id' => 'required|exists:size_charts,id',
        ]);

        $size_chart = SizeChart::findOrFail($request->size_chart_id);

        if ($request->whole_category) {
            $size_chart->category_id = $request->category_id;
            $size_chart->product_ids = null;
        } else {
            $size_chart->category_id = null;
            $size_chart->product_ids = json_encode($request->checked_ids ?? []);
        }
        $size_chart->save();

        return response()->json(['success' => true]);
    }
}
