<?php

namespace App\Models\Admin\SiteContent;

use Illuminate\Database\Eloquent\Model;

class Sitecontent extends Model
{
    //
    protected $fillable = [
        'code','content_english','content_arabic',
    ];
}
