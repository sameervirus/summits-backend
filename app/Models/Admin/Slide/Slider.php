<?php

namespace App\Models\Admin\Slide;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    //
    protected $fillable = [
        'image','header','caption','sort',
    ];
}
