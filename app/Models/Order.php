<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function products() {
        return $this->belongsToMany(Product::class)->withPivot(
                'name',
                'quantity',
                'price'
            );;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }

    public function status() {
        return $this->belongsTo(Status::class, 'id', 'status_id', 'order_status');
    }
}
