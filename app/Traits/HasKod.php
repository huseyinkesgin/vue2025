<?php 

namespace App\Traits;

use Illuminate\Support\Str;

trait HasKod
{
    protected static function bootHasKod()
    {
        static::creating(function ($model) {
            if (empty($model->kod)) {
                $model->kod = strtoupper(class_basename($model)) . '-' . str_pad(static::max('id') + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
