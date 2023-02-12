<?php

namespace s\Admin\Pdata;

use Illuminate\Database\Eloquent\Model;

class Fluid extends Model
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