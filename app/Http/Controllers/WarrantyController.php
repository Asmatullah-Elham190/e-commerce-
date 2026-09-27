<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\WarrantyTranslation;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:view_product_warranties'])->only('index');
        $this->middleware(['permission:edit_product_warranty'])->only('edit');
        $this->middleware(['permission:delete_product_warranty'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $warranty_tabs = ['All Warranties'];
        $warranties = Warranty::orderBy('created_at', 'desc');

        if ($request->search != null){
            $sort_search = $request->search;
            $warranties = $warranties->where('text', 'like', '%'.$request->search.'%');
        }

        $warranties = $warranties->paginate(15);
        return view('backend.product.warranties.index', compact('warranties', 'sort_search', 'warranty_tabs'));

    }

    public function filter(Request $request)
    {
        $warranties = Warranty::orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $warranties = $warranties->where(function ($query) use ($sort_search) {
                $query->where('text', 'like', '%' . $sort_search . '%');
            });
        }

        $warranties = $warranties->paginate(15);
        $view = view(
            'backend.product.warranties.table',
            compact('warranties', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create()
    {
        return view('backend.product.warranties.create');
    }

    public function store(Request $request)
    {
        $warranty = new Warranty();
        $warranty->text = $request->text;
        $warranty->logo = $request->logo;
        $warranty->save();

        $warranty_translation = WarrantyTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'warranty_id' => $warranty->id]);
        $warranty_translation->text = $request->text;
        $warranty_translation->save();

        return 1;
    }

    public function edit(Request $request)
    {
        $lang   = $request->lang ?? env("DEFAULT_LANGUAGE");
        $warranty  = Warranty::findOrFail($request->id);
        return view('backend.product.warranties.edit', compact('warranty','lang'));
    }

    public function update(Request $request)
    {
        $warranty = Warranty::findOrFail($request->id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $warranty->text = $request->text;
        }
        $warranty->logo = $request->logo;
        $warranty->save();

        $warranty_translation = WarrantyTranslation::firstOrNew(['lang' => $request->lang, 'warranty_id' => $warranty->id]);
        $warranty_translation->text = $request->text;
        $warranty_translation->save();

        return 1;
    }

    public function destroy($id)
    {
        $warranty = Warranty::findOrFail($id);
        $warranty->warranty_translations()->delete();
        Warranty::destroy($id);

        return 1;
    }

    public function bulk_warranty_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $warranty_id) {

                $warranty = Warranty::find($warranty_id);

                if (!$warranty) {
                    continue;
                }

                foreach ($warranty->warranty_translations as $warranty_translation) {
                    $warranty_translation->delete();
                }

                $warranty->delete();
            }

            return 1;
        }

        return 0;
    }

    public function admin_ajax_add_warranty_modal(Request $request)
    {
        return view('backend.product.warranties.ajax_add_warranty_modal');
    }

    public function admin_ajax_add_warranty_store(Request $request)
    {
        $warranty = new Warranty();
        $warranty->text = $request->warranty_text;
        $warranty->logo = $request->warranty_logo;
        $warranty->save();

        $warranty_translation = WarrantyTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'warranty_id' => $warranty->id]);
        $warranty_translation->text = $request->warranty_text;
        $warranty_translation->save();

        return response()->json([
            'success'        => true,
            'message'        => translate('Warranty has been inserted successfully'),
            'warranty_id'    => $warranty->id,
            'warranty_text'  => $warranty->text,
        ]);
    }
}
