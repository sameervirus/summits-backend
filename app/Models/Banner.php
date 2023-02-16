<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];
    protected $appends = ['title', 'description', 'btnText'];


    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_arabic : $this->title_english;
    }

    public function getDescriptionAttribute()
    {
        return App::getLocale() == 'ar' ? $this->description_arabic : $this->description_english;
    }

    public function getBtnTextAttribute()
    {
        return App::getLocale() == 'ar' ? $this->btnText_arabic : $this->btnText_english;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('hero')
            ->performOnCollections('hero')
            ->fit(Manipulations::FIT_CROP, 1740, 562)
            ->nonQueued();

        $this->addMediaConversion('banner1')
            ->performOnCollections('banner1')
            ->fit(Manipulations::FIT_CROP, 840, 240)
            ->nonQueued();

        $this->addMediaConversion('banner2')
            ->performOnCollections('banner2')
            ->fit(Manipulations::FIT_CROP, 1130, 240)
            ->nonQueued();

        $this->addMediaConversion('icons')
            ->performOnCollections('icons')
            ->fit(Manipulations::FIT_CROP, 190, 190)
            ->nonQueued();
    }
}
