<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class GuestOtpVerification extends Model
{
    use PreventDemoModeChanges;
    protected $table = 'guest_otp_verifications';

    protected $guarded = [];
}