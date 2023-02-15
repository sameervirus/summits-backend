<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Tag extends Model
{
    protected $guarded = [];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return App::getLocale() == 'ar' ? $this->name_arabic : $this->name_english;
    }
}
