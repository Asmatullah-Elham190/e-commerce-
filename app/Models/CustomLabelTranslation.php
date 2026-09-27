<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class CustomLabelTranslation extends Model
{
    use PreventDemoModeChanges;
    protected $fillable = ['text', 'lang', 'custom_label_id'];

    protected $table = 'custom_label_translations';
}
