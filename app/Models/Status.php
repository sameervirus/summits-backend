<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Status extends Model
{
    protected $guarded = [];
    protected $table = 'status';

    protected $appends = ['serial', 'name'];
    
    public function getSerialAttribute()
    {
        return $this->id;
    }

    public function getNameAttribute()
    {
        return App::getLocale() == 'ar' ? $this->arabic_name : $this->english_name;
    }
}
