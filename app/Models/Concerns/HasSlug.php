<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Membuat slug unik otomatis dari kolom sumber ($slugSource, default "judul")
 * saat slug masih kosong. Slug tidak berubah ketika judul diedit (aman untuk SEO).
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $source = $model->slugSource ?? 'judul';
                $model->slug = $model->uniqueSlug(Str::slug((string) $model->{$source}) ?: Str::lower(Str::random(8)));
            }
        });
    }

    public function uniqueSlug(string $base): string
    {
        $base = Str::limit($base, 180, '');
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
