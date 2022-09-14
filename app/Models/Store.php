<?php

namespace App\Models;

use AliexpressSolutionOrderGetRequest;
use AliexpressSolutionOrderInfoGetRequest;
use App\Ali;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OrderDetailQuery;
use OrderQuery;

class Store extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'store_name', 'store_id', 'store_url', 'last_auth', 'access_token', 'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function checkAccessToken(): bool
    {
        return (bool)$this->getMerchantProfile();

    }

    public function getMerchantProfile()
    {
        return getMerchantProfile($this->access_token);
    }

    public function getOrders(OrderQuery $param0)
    {
        $req = new AliexpressSolutionOrderGetRequest;
        $req->setParam0(json_encode($param0));
        return $this->aeRequest($req);
    }


    public function aeRequest(object $request)
    {
        return (new Ali($this->access_token))->doRequest($request);
    }

    public function getOrderDetail($order_id)
    {
        $req = new AliexpressSolutionOrderInfoGetRequest;
        $param1 = new OrderDetailQuery;
        $param1->ext_info_bit_flag = '11111';
        $param1->order_id = (string)$order_id;
        $req->setParam1(json_encode($param1));
        return $this->aeRequest($req);
    }
}
