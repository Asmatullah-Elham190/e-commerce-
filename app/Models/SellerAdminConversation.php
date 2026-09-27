<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App\Models\SellerAdminMessage;

class SellerAdminConversation extends Model
{
    use PreventDemoModeChanges;
    protected $guarded = [];
    protected $table = 'seller_admin_conversations';

    public function messages()
    {
        return $this->hasMany(SellerAdminMessage::class, 'seller_admin_conversation_id')->orderBy('created_at', 'asc');
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
