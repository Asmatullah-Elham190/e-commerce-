<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminNotice extends Model
{
    protected $table = 'seller_admin_notices';

    protected $guarded = [];

    public function sellers()
    {
        return $this->belongsToMany(
            User::class,
            'seller_admin_notice_sellers',
            'seller_admin_notice_id',
            'seller_id'
        )->withPivot('seen')->withTimestamps();
    }

    public function seenSellers()
    {
        return $this->sellers()->wherePivot('seen', 1);
    }

    public function getNoticeTypeLabelAttribute()
    {
        return match ($this->notice_type) {
            'temporary' => translate('Temporary Notice'),
            'permanent' => translate('Permanent Notice'),
            'default'   => translate('Default Notice'),
            default     => translate('Notice'),
        };
    }
    public function getBgColorHexAttribute()
    {
        $map = [
            'Light Blue'   => '#f1fafd',
            'Light Pink'   => '#fff4f8',
            'Light Green'  => '#e4ffea',
            'Light Yellow' => '#fff9e3',
            'Light Gray'   => '#f2f2f8',
        ];

        return $map[$this->bg_color] ?? '#007bff26';
    }

    public function getNoticeDatetimeFormattedAttribute()
    {
        if (empty($this->notice_datetime)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($this->notice_datetime)->format('M d, Y');
        } catch (\Exception $e) {
            return $this->notice_datetime;
        }
    }
}