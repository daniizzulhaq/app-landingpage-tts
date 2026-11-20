<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use App\Services\ImageService;
use App\Support\Html;

class SettingController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function edit()
    {
        return view('admin.settings.edit', [
            'groups' => config('site.settings'),
            'values' => Setting::pluck('value', 'key')->all(),
        ]);
    }

    public function update(SettingRequest $request)
    {
        $current = Setting::pluck('value', 'key')->all();

        foreach (config('site.settings') as $fields) {
            foreach ($fields as $key => $field) {
                switch ($field['type']) {
                    case 'image':
                        $old = $current[$key] ?? null;
                        if ($request->boolean('hapus_'.$key)) {
                            $this->images->delete($old);
                            Setting::put($key, null);
                        }
                        if ($request->hasFile($key)) {
                            Setting::put($key, $this->images->replace($request->file($key), $old, 'settings'));
                        }
                        break;
                    case 'boolean':
                        Setting::put($key, $request->boolean($key) ? '1' : '0');
                        break;
                    case 'editor':
                        Setting::put($key, Html::clean($request->input($key)));
                        break;
                    default:
                        $value = $request->input($key);
                        if ($key === 'map_embed') {
                            $value = self::mapEmbedSrc($value);
                        }
                        Setting::put($key, $value);
                }
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Ambil hanya URL "src" embed Google Maps dari kode iframe yang ditempel admin.
     * Hanya domain google.com yang diterima agar aman ditampilkan di iframe.
     */
    public static function mapEmbedSrc(?string $input): ?string
    {
        if (! $input) {
            return null;
        }
        if (preg_match('/src=["\']([^"\']+)["\']/i', $input, $m)) {
            $input = $m[1];
        }
        $input = html_entity_decode(trim($input));
        $host = parse_url($input, PHP_URL_HOST) ?: '';

        return str_starts_with($input, 'https://') && preg_match('/(^|\.)google\.[a-z.]+$/i', $host) ? $input : null;
    }
}
