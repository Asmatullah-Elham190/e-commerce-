<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ElementType extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'is_default'
    ];

    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    public function image()
    {
        return $this->belongsTo(Upload::class,);
    }

    public function element_styles()
    {
        return $this->hasMany(ElementStyle::class);
    }
}
