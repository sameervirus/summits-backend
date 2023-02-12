<?php

namespace App\Models\Admin\SiteContent;

use Illuminate\Database\Eloquent\Model;

class Sitecontent extends Model
{
    //
    protected $fillable = [
        'code','content','lang',
    ];
}
