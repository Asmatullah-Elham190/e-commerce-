<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class Element extends Model
{
    use PreventDemoModeChanges;

    protected $with = ['element_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $element_translation = $this->element_translations->where('lang', $lang)->first();
        return $element_translation != null ? $element_translation->$field : $this->$field;
    }

    public function element_translations()
    {
        return $this->hasMany(ElementTranslation::class);
    }

    public function element_types()
    {
        return $this->hasMany(ElementType::class);
    }
}
