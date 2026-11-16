<?php

namespace App\Models\Concerns;

trait MencatatWaktu
{
    public static function bootMencatatWaktu(): void
    {
        static::creating(function ($model) {
            if (! $model->created_at) {
                $model->created_at = now();
            }
        });
    }
}
