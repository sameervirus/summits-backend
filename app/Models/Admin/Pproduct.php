<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Pproduct extends Model implements HasMedia
{

  use HasMediaTrait;

  protected $table = 'pproducts';
  protected $appends = ['images', 'url_cover', 'downloads'];
  protected $hidden = ["media"];

  public function registerMediaConversions(Media $media = null)
  {
      $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
  }

  public function getImagesAttribute()
  {
      if(count($this->getMedia('images')) < 1) return null;
      foreach ($this->getMedia('images') as $image) {
          $item[] = ['thumb' => $image->getUrl('thumb'), 'image' => $image->getUrl()];
      };
      return $item;
  }

  public function getDownloadsAttribute()
  {
      if(count($this->getMedia('download')) < 1) return null;
      foreach ($this->getMedia('download') as $pdf) {
          $item[] = ['code' => $pdf->getCustomProperty('code'), 'url' => $pdf->getUrl()];
      };
      return $item;
  }

  public function getUrlCoverAttribute()
  {
      return (empty($this->getMedia('images')[0])) ? "" : $this->getMedia('images')[0]->getFullUrl();
  }

  protected $fillable = [
      'types_slug','types','types_ar','category_slug', 'category', 'category_ar', 'sub_slug', 'sub', 'sub_ar', 'slug', 'name', 'name_ar', 'features', 'features_ar', 'description', 'description_ar', 'technical_data', 'technical_data_ar', 'sort_order',
  ];

  public function applications()
  {
      return $this->belongsToMany('s\Admin\Pdata\Application');
  }

  public function arapplications()
  {
      return $this->belongsToMany('s\Admin\Pdata\Arapplication');
  }

  public function fluids()
  {
      return $this->belongsToMany('s\Admin\Pdata\Fluid');
  }

  public function arfluids()
  {
      return $this->belongsToMany('s\Admin\Pdata\Arfluid');
  }
}
