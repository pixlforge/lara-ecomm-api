<?php

namespace App\Models\Traits;

trait CanBeDefault
{
    /**
     * Model boot method.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->default) {
                $model->newQuery()->where('user_id', $model->user->id)->update([
                    'default' => false
                ]);
            }
        });
    }

    /**
     * Set the default attribute.
     *
     * @param mixed $value
     * @return void
     */
    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = $value === 'true' || $value === true || $value == 1 ? true : false;
    }
}