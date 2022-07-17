<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    public $translatable = ['name', 'description'];
    protected $appends = ['name_locale', 'desc_locale'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'include_vat',
        'price',
        'store_id',
        'display'
    ];

    protected function getNameLocaleAttribute()
    {
        $locale = App::currentLocale();
        return $this->getTranslation('name', $locale);
    }

    protected function getdescLocaleAttribute()
    {
        $locale = App::currentLocale();
        return $this->getTranslation('description', $locale);
    }
}
