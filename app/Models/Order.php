<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivery_time' => 'datetime',
    ];

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
        return $this->belongsTo(Status::class);
    }
}
