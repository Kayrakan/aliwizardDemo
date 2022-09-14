<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $fillable = [//
        'order_status', 'order_date', 'country', 'amount', 'order_id', 'name', 'store_id', 'end_reason',
        'buyer_login_id', 'image','tag'
    ];
    public $casts = [
        'pivot_sku' => 'array',
        'pivot.price' => 'integer'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderProducts(): BelongsToMany
    {
        return $this->belongsToMany(OrderProduct::class, 'order_order_product')
            ->withPivot(['price', 'title', 'quantity', 'sku']);
    }

    public function tag()
    {
        return $this->belongsTo(OrderTag::class,'tag','id',);
    }
}
