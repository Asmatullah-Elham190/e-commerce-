<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'notice_type'         => $this->notice_type,
            'notice_type_label'   => $this->notice_type_label ?? null,
            'message'             => $this->message,
            'bg_color'            => $this->bg_color,
            'bg_color_hex'        => $this->bg_color_hex ?? null,
            'notice_datetime'     => $this->notice_datetime,
            'notice_datetime_formatted' => $this->notice_datetime_formatted ?? null,
            'expiry_date'         => $this->expiry_date ?? null,
            'save_as_preset'      => (bool) $this->save_as_preset,
            'created_at'          => $this->created_at?->format('M d Y, h:i a'),
        ];
    }
}