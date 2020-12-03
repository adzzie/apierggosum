<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->{$model->getKeyName()} = (string) Str::orderedUuid(); //Str::uuid();
            } catch (Exception $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
