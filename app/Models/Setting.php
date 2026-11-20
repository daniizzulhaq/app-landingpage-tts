<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const CACHE_KEY = 'site_settings';

    private const MEMO = 'settings.memo';

    /** Semua pengaturan (cache permanen + memo per-request di container). */
    public static function allCached(): array
    {
        if (app()->bound(self::MEMO)) {
            return app(self::MEMO);
        }
        try {
            $all = Cache::rememberForever(self::CACHE_KEY, fn () => static::pluck('value', 'key')->all());
        } catch (Throwable) {
            return [];
        }
        app()->instance(self::MEMO, $all);

        return $all;
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function flushCache(): void
    {
        app()->forgetInstance(self::MEMO);
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::flushCache());
        static::deleted(fn () => self::flushCache());
    }
}
