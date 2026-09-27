<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class TopBannerTranslation extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = ['text', 'lang', 'top_banner_id'];
    protected $table = 'top_banner_translations';
}
