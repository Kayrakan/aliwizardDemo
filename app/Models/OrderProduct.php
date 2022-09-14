<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderProduct extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $fillable = [
        'product_id', 'store_id', 'product_name', 'quantity', 'sku', 'price', 'order_id', 'category_id'
    ];
    public $casts = [
        'pivot.sku' => 'array'
    ];

    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_order_product')
            ->withPivot(['price', 'title', 'quantity', 'sku']);

    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}

