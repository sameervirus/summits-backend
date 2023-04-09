<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class City extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $appends = ['value', 'label'];
    public function getValueAttribute()
    {
        return $this->id;
    }

    public function getLabelAttribute()
    {
        return App::getLocale() == 'ar' ? $this->city_name_ar : $this->city_name_en;
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
