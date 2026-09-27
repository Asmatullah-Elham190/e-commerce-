<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\ElementStyle;
use App\Models\ElementTranslation;
use App\Models\ElementType;
use Illuminate\Http\Request;
use CoreComponentRepository;

class ElementController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_elements'])->only('index');
        $this->middleware(['permission:add_elements'])->only('create');
        $this->middleware(['permission:edit_elements'])->only('edit');
        $this->middleware(['permission:delete_elements '])->only('destroy');
    }

    public function index(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        $elements = Element::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.website_settings.pages.element.index', compact('elements'));
    }

    public function create() {}

    public function store(Request $request)
    {
        $element = new Element();
        $element->name = $request->name;
        $element->save();

        $element_translation = ElementTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'element_id' => $element->id]);
        $element_translation->name = $request->name;
        $element_translation->save();

        flash(translate('Element has been inserted successfully'))->success();
        return redirect()->route('elements.index');
    }

    public function show($id)
    {
        $data['element'] = Element::findOrFail($id);
        $data['all_element_types'] = ElementType::with('element')->where('element_id', $id)->get();

        // echo '<pre>';print_r($data['all_attribute_values']);die;

        return view("backend.website_settings.pages.element.element_type.index", $data);
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $element = Element::findOrFail($id);
        return view('backend.website_settings.pages.element.edit', compact('element', 'lang'));
    }

    public function update(Request $request, $id)
    {
        $element = Element::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $element->name = $request->name;
        }
        $element->save();

        $element_translation = ElementTranslation::firstOrNew(['lang' => $request->lang, 'element_id' => $element->id]);
        $element_translation->name = $request->name;
        $element_translation->save();

        flash(translate('Element has been updated successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        $element = Element::findOrFail($id);

        foreach ($element->element_translations as $key => $element_translation) {
            $element_translation->delete();
        }

        Element::destroy($id);
        flash(translate('Element has been deleted successfully'))->success();
        return redirect()->route('elements.index');
    }

    public function store_element_type(Request $request)
    {
        $element_type = new ElementType();
        $element_type->element_id = $request->element_id;
        $element_type->name = ucfirst($request->type);
        $element_type->save();

        flash(translate('Element type has been inserted successfully'))->success();
        return redirect()->route('elements.show', $request->element_id);
    }

    public function edit_element_type(Request $request, $id)
    {
        $element_type = ElementType::findOrFail($id);
        return view("backend.website_settings.pages.element.element_type.edit", compact('element_type'));
    }

    public function update_element_type(Request $request, $id)
    {
        $element_type = ElementType::findOrFail($id);

        $element_type->element_id = $request->element_id;
        $element_type->name = ucfirst($request->type);

        $element_type->save();

        flash(translate('Element Type has been updated successfully'))->success();
        return back();
    }

    public function destroy_element_type($id)
    {
        $element_types = ElementType::findOrFail($id);
        ElementType::destroy($id);

        flash(translate('Element Types has been deleted successfully'))->success();
        return redirect()->route('elements.show', $element_types->element_id);
    }

    public function show_element_style($id)
    {
        $element_type = ElementType::findOrFail($id);
        $all_element_styles = ElementStyle::where('element_type_id', $id)->get();

        // Create key => value map for easy access in blade
        $style_values = $all_element_styles->pluck('value', 'name');

        return view("backend.website_settings.pages.element.element_style.index", [
            'element_type' => $element_type,
            'all_element_styles' => $all_element_styles,
            'style_values' => $style_values
        ]);
    }

    public function store_element_style(Request $request)
    {
        $element_type_id = $request->element_type_id;

        foreach ($request->names as $style_key) {
            $style_value = $request->$style_key ?? '#000000'; // default black

            // Check if the style already exists
            $element_style = ElementStyle::where('element_type_id', $element_type_id)
                ->where('name', $style_key)
                ->first();

            if ($element_style) {
                // Update existing
                $element_style->value = $style_value;
            } else {
                // Insert new
                $element_style = new ElementStyle();
                $element_style->element_type_id = $element_type_id;
                $element_style->name = $style_key;
                $element_style->value = $style_value;
            }

            $element_style->save();
        }

        flash(translate('Element styles have been saved successfully'))->success();
        return redirect()->back();
    }

    public function destroy_element_style($id)
    {
        $element_styles = ElementStyle::findOrFail($id);
        ElementStyle::destroy($id);

        flash(translate('Element styles has been deleted successfully'))->success();
        return redirect()->back();
    }
}
