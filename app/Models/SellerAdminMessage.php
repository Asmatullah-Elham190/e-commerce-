<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;

class SellerAdminMessage extends Model
{
    use PreventDemoModeChanges;
    protected $table = 'seller_admin_messages';

    protected $guarded = [];

    public function conversation(){
        return $this->belongsTo(SellerAdminConversation::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
