<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTag extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $fillable = ['name','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class,'tag','id');
    }
}
