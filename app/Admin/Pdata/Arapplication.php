<?php

namespace App\Admin\Pdata;

use Illuminate\Database\Eloquent\Model;

class Arapplication extends Model
{
    
    protected $fillable = [
        'slug','name',
    ];

    public $timestamps = false;

    public function pproducts()
    {
        return $this->belongsToMany('App\Admin\Pproduct');
    }

}