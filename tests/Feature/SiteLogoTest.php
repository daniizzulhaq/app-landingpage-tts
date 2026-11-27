<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteLogoTest extends TestCase
{
    public function test_old_seeded_logo_uses_tracked_asset_even_when_old_file_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/logo-polres-tts.png', 'old logo');
        app()->instance('settings.memo', ['logo' => 'settings/logo-polres-tts.png']);

        $url = site_logo_url();

        $this->assertStringStartsWith(asset('img/logo-polda-ntt.png').'?v=', $url);
        $this->assertStringNotContainsString('/storage/', $url);
        $version = substr(hash_file('sha256', public_path('img/logo-polda-ntt.png')), 0, 12);
        $this->assertStringEndsWith('?v='.$version, $url);
    }

    public function test_bundled_logo_does_not_require_an_uploaded_copy(): void
    {
        Storage::fake('public');
        app()->instance('settings.memo', ['logo' => 'settings/logo-polda-ntt.png']);

        $this->assertStringStartsWith(asset('img/logo-polda-ntt.png').'?v=', site_logo_url());
    }

    public function test_admin_uploaded_logo_is_preserved(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/custom-logo.webp', 'custom logo');
        app()->instance('settings.memo', ['logo' => 'settings/custom-logo.webp']);

        $this->assertSame(asset('storage/settings/custom-logo.webp'), site_logo_url());
    }

    public function test_missing_or_unset_logo_uses_tracked_asset(): void
    {
        Storage::fake('public');

        foreach ([null, 'settings/missing.webp'] as $path) {
            app()->instance('settings.memo', ['logo' => $path]);

            $this->assertStringStartsWith(asset('img/logo-polda-ntt.png').'?v=', site_logo_url());
        }
    }
}
