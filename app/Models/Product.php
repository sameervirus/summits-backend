<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['name', 'description'];


    public function getNameAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_arabic : $this->title_english;
    }

    public function getDescriptionAttribute()
    {
        return App::getLocale() == 'ar' ? $this->description_arabic : $this->description_english;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('original')
            ->fit(Manipulations::FIT_CROP, 1000, 1000)
            ->nonQueued();
        $this->addMediaConversion('thumbnail')
            ->fit(Manipulations::FIT_CROP, 256, 256)
            ->nonQueued();
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function tags() {
        return $this->hasMany(Tag::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }

}
