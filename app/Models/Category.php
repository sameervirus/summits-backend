<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['name'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
              ->fit(Manipulations::FIT_CROP, 80, 80)
              ->nonQueued();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function childs() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getNameAttribute()
    {
        return App::getLocale() == 'ar' ? $this->name_arabic : $this->name_english;
    }
}
