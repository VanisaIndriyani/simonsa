<?php

namespace App\Support;

class Simonsa
{
    public static function logo(): ?string
    {
        foreach (['logo.png', 'logo.jpg', 'logo.jpeg', 'logo.webp', 'logo.svg', 'logo'] as $berkas) {
            if (is_file(public_path('img/'.$berkas))) {
                return asset('img/'.$berkas);
            }
        }

        return null;
    }
}
