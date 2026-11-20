<?php

namespace App\Http\Requests\Admin;

class SettingRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        $rules = [];
        foreach (config('site.settings') as $fields) {
            foreach ($fields as $key => $field) {
                $rules[$key] = match ($field['type']) {
                    'image' => ['nullable', ...self::IMAGE],
                    'boolean' => ['nullable', 'boolean'],
                    default => explode('|', $field['rules'] ?? 'nullable|string|max:1000'),
                };
                if ($field['type'] === 'image') {
                    $rules['hapus_'.$key] = ['nullable', 'boolean'];
                }
            }
        }

        return $rules;
    }

    public function after(): array
    {
        return [function ($validator) {
            $embed = $this->input('map_embed');
            if ($embed && ! \App\Http\Controllers\Admin\SettingController::mapEmbedSrc($embed)) {
                $validator->errors()->add('map_embed', 'Embed peta harus berupa kode/URL embed dari Google Maps (https://www.google.com/maps/embed?...).');
            }
        }];
    }

    public function attributes(): array
    {
        return collect(config('site.settings'))->collapse()->map(fn ($f) => strtolower($f['label']))->all();
    }
}
