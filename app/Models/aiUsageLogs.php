<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class aiUsageLogs extends Model
{
    use HasFactory;
    use PreventDemoModeChanges;
    protected $fillable = [
        'user_id',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'model'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
