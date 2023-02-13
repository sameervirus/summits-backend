<?php

namespace App\Models\Admin\Pdata;

use Illuminate\Database\Eloquent\Model;

class Arapplication extends Model
{

    protected $fillable = [
        'slug','name',
    ];

    public $timestamps = false;

    public function pproducts()
    {
        return $this->belongsToMany('s\Admin\Pproduct');
    }

}
