<?php

namespace Bvipul\EncryptsAttributes\Traits;

use Illuminate\Contracts\Encryption\DecryptException;

trait EncryptsAttributes
{
    // Since, Laravel uses env('APP_KEY') for it's internal processes, and also
    // the encrypt/decrypt functions. Hence, we need to make sure, we use the
    // same key to encrypt or decrypt other wise we'll get an error. You
    // need to make sure, every developer in your team has the same
    // key in their env.
    protected static function boot()
    {
        parent::boot();

        // This event is called when a new record is getting created or updated
        static::saving(function($model) {
            $attributes = $model->getFillableAttributes();

            foreach($attributes as $key => $attribute) {
                $model->$key = encrypt($attribute);
            }
        });

        // This event is called when we retrieve any record from the database
        static::retrieved(function($model) {
            $attributes = $model->getFillableAttributes();
            
            foreach($attributes as $key => $attribute) {
                try {
                    $model->$key = decrypt($attribute);
                } catch (DecryptException $e) {
                    $model->$key = $attribute;
                }
            }
        });

    }

    /**
     * Get only fillable attributes
     */
    private function getFillableAttributes()
    {
        $model = $this;

        $attributes = collect($model->attributes)->filter(
            function($attribute, $key) use ($model) {
                return in_array($key, $model->fillable);
            }
        );

        return $attributes;
    }
}