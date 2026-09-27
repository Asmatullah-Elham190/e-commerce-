<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Http\Requests\ColorRequest;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Color;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use CoreComponentRepository;
use Str;

class AttributeController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:view_product_attributes'])->only('index');
        $this->middleware(['permission:edit_product_attribute'])->only('edit');
        $this->middleware(['permission:delete_product_attribute'])->only('destroy');

        $this->middleware(['permission:view_product_attribute_values'])->only('show');
        $this->middleware(['permission:edit_product_attribute_value'])->only('edit_attribute_value');
        $this->middleware(['permission:delete_product_attribute_value'])->only('destroy_attribute_value');

        $this->middleware(['permission:view_colors'])->only('colors');
        $this->middleware(['permission:add_color'])->only('colors_create');
        $this->middleware(['permission:edit_color'])->only('edit_color');
        $this->middleware(['permission:delete_color'])->only('destroy_color');
    }

    public function index(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        $sort_search = null;
        $attribute_tabs = ['All Attributes'];
        $attributes = Attribute::with('attribute_values')->orderBy('created_at', 'desc');
        if ($request->search != null){
            $sort_search = $request->search;
            $attributes = $attributes->where('name', 'like', '%'.$request->search.'%');
        }

        $attributes = $attributes->paginate(15);
        return view('backend.product.attribute.index', compact('attributes', 'attribute_tabs', 'sort_search'));
    }

    public function filter(Request $request)
    {
        $attributes = Attribute::with('attribute_values')->orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $attributes = $attributes->where(function ($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%');
            });
        }

        $attributes = $attributes->paginate(15);
        $view = view(
            'backend.product.attribute.table',
            compact('attributes', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create(Request $request)
    {
        return view('backend.product.attribute.create');
    }

    public function store(AttributeRequest $request)
    {
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        if ($request->has('attribute_values')) {
            foreach ($request->attribute_values as $value) {
                if (!empty($value)) {
                    $attribute_value = new AttributeValue;
                    $attribute_value->attribute_id = $attribute->id;
                    $attribute_value->value = ucfirst($value);
                    $attribute_value->save();
                }
            }
        }

        return 1;
    }

    public function edit(Request $request)
    {
        $lang      = $request->lang ?? env("DEFAULT_LANGUAGE");
        $attribute = Attribute::with('attribute_values')->findOrFail($request->id);
        return view('backend.product.attribute.edit', compact('attribute','lang'));
    }

    public function update(AttributeRequest $request)
    {
        $attribute = Attribute::findOrFail($request->id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
          $attribute->name = $request->name;
        }
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => $request->lang, 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();
        $this->updateAttributeValues($attribute->id, $request->attribute_values ?? [], $request->attribute_value_ids ?? []);
        return 1;
    }

    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute = Attribute::where('id', $id)->first();
        if (!is_null($attribute)) {
            foreach ($attribute->attribute_translations as $key => $attribute_translation) {
                $attribute_translation->delete();
            }

            Attribute::destroy($id);
            AttributeValue::where('attribute_id', $id)->delete();
        }
        return 1;

    }

    public function bulk_attribute_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $attribute_id) {

                $attribute = Attribute::find($attribute_id);

                if (!$attribute) {
                    continue;
                }

                foreach ($attribute->attribute_translations as $attribute_translation) {
                    $attribute_translation->delete();
                }

                AttributeValue::where('attribute_id', $attribute_id)->delete();

                $attribute->delete();
            }

            return 1;
        }

        return 0;
    }

    private function updateAttributeValues($attribute_id, $values, $ids)
    {
        $existing_ids = AttributeValue::where('attribute_id', $attribute_id)->pluck('id')->toArray();

        foreach ($values as $index => $value) {
            $id = $ids[$index] ?? null;
            $value = trim($value);
            if (empty($value)) continue;

            if ($id) {
                $attribute_value = AttributeValue::findOrFail($id);
                $attribute_value->attribute_id = $attribute_id;
                $attribute_value->value = ucfirst($value);
                $attribute_value->save();
            } else {
                $attribute_value = new AttributeValue();
                $attribute_value->attribute_id = $attribute_id;
                $attribute_value->value = ucfirst($value);
                $attribute_value->save();
            }
        }

        $ids_to_delete = array_diff($existing_ids, array_filter($ids));
        if (!empty($ids_to_delete)) {
            AttributeValue::whereIn('id', $ids_to_delete)->delete();
        }
    }

    public function admin_ajax_add_attribute_modal(Request $request)
    {
        return view('backend.product.attribute.ajax_add_attribute_modal');
    }

    public function admin_ajax_add_attribute_store(Request $request)
    {
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        if ($request->has('attribute_values')) {
            foreach ($request->attribute_values as $value) {
                if (!empty($value)) {
                    $attribute_value = new AttributeValue;
                    $attribute_value->attribute_id = $attribute->id;
                    $attribute_value->value = ucfirst($value);
                    $attribute_value->save();
                }
            }
        }

        return response()->json([
            'success'        => true,
            'message'        => translate('Attribute has been inserted successfully'),
            'attribute_id'    => $attribute->id,
            'attribute_name'  => $attribute->name,
            'attribute_values' => $attribute->attribute_values->map(function($v) {
                return ['id' => $v->id, 'value' => $v->value];
            }),
        ]);
    }
    
    public function colors(Request $request) {
        $sort_search = null;
        $color_tabs = ['All Colors'];
        $colors = Color::orderBy('created_at', 'desc');

        if ($request->search != null){
            $sort_search = $request->search;
            $colors = $colors->where('name', 'like', '%'.$request->search.'%');
        }

        $colors = $colors->paginate(15);
        return view('backend.product.color.index', compact('colors', 'sort_search', 'color_tabs'));
    }

    public function colors_filter(Request $request)
    {
        $colors = Color::orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $colors = $colors->where(function ($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%');
            });
        }

        $colors = $colors->paginate(15);
        $view = view(
            'backend.product.color.table',
            compact('colors', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function colors_create(Request $request) {
        return view('backend.product.color.create');
    }
    
    public function store_color(ColorRequest $request) {
        
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        
        $color->save();
        return 1;
    }
    
    public function edit_color(Request $request)
    {
        $color = Color::findOrFail($request->id);
        return view('backend.product.color.edit', compact('color'));
    }

    public function update_color(ColorRequest $request)
    {
        $color = Color::findOrFail($request->id);
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        
        $color->save();
        return 1;
    }
    
    public function destroy_color($id)
    {
        $color = Color::findOrFail($id);
        $color = Color::where('id', $id)->first();

        if (!is_null($color)) {
            $color->delete();
        }
        
        return 1;
    }

    public function bulk_color_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $color_id) {
                Color::destroy($color_id);
            }

            return 1;
        }

        return 0;
    }
    
    public function admin_ajax_add_color_modal(Request $request)
    {
        return view('backend.product.color.ajax_add_color_modal');
    }

    public function admin_ajax_add_color_store(Request $request)
    {
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;
        
        $color->save();

        return response()->json([
            'success'        => true,
            'message'        => translate('Color has been inserted successfully'),
            'color_code'    => $color->code,
            'color_name'  => $color->name,
        ]);
    }
    
}
