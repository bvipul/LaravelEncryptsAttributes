<?php

namespace Bvipul\EncryptsAttributes\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\EncryptException;
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

        static::addGlobalScope(function($builder) {
            // dd($builder->getQuery()->bindings);
            $bindingTypes = $builder->getQuery()->bindings;
            // dd($bindingTypes);

            $encrypted_bindings = [];
            
            // if(count($bindings) > 0) {
            foreach($bindingTypes as $type => $bindings) {
                // dd($type, $binding);
                if(count($bindings) > 0) {
                    foreach($bindings as $binding) {
                        $encrypted_bindings[$type][] = Crypt::encrypt($binding);        
                    }
                } else {
                    $encrypted_bindings[$type] = [];
                }
            }    
            // }
            // $builder->getQuery->addBinding()
            dd($encrypted_bindings);
            $builder->getQuery()->setBindings($encrypted_bindings);
        });

        // This event is called when a new record is getting created or updated
        static::saving(function($model) {
            $attributes = $model->getEncryptionAttributes();

            foreach($attributes as $key => $attribute) {
                try {
                    $model->$key = Crypt::encrypt($attribute);
                } catch (EncryptException $e) {
                    throw $e;
                }
            }
        });

        // This event is called when we retrieve any record from the database
        static::retrieved(function($model) {
            $attributes = $model->getEncryptionAttributes();
            
            foreach($attributes as $key => $attribute) {
                try {
                    $model->$key = Crypt::decrypt($attribute);
                } catch (DecryptException $e) {
                    throw $e;
                }
            }
        });

    }

    /**
     * Get only encryption attributes
     */
    private function getEncryptionAttributes()
    {
        $model = $this;

        if(isset($model->encrypted_attributes))
        {
            $attributes = collect($model->attributes)->filter(
                function($attribute, $key) use ($model) {
                    return in_array($key, $model->encrypted_attributes);
                }
            );

            return $attributes;
        }

        return [];
    }
}