<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminRequest extends Model
{
    protected $table = 'seller_admin_requests';

    protected $guarded = [];

    public function getItemLabelAttribute()
    {
        $map = [
            'category_name'          => 'Category',
            'brand_name'             => 'Brand',
            'color_name'             => 'Color',
            'attribute_name'         => 'Attribute',
            'unit_name'              => 'Unit',
            'measurement_point_name' => 'Measurement Point',
            'warranty_name'          => 'Warranty',
        ];

        foreach ($map as $field => $label) {
            if (!empty($this->{$field})) {
                return [
                    'type'  => $label,
                    'value' => $this->{$field},
                ];
            }
        }

        return null;
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}