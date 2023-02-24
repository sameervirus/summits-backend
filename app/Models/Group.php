<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Group extends Model
{
    protected $guarded = [];

    protected $appends = ['title', 'description'];

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_arabic : $this->title_english;
    }

    public function getDescriptionAttribute()
    {
        return App::getLocale() == 'ar' ? $this->description_arabic : $this->description_english;
    }

    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
