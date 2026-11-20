<?php

namespace Tests\Feature;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function super(): User
    {
        return User::where('email', 'admin@polrestts.test')->first();
    }

    private function operator(): User
    {
        return User::where('email', 'operator.lantas@polrestts.test')->first();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get(route('admin.login'))->assertOk();
    }

    public function test_login_works(): void
    {
        $this->post(route('admin.login.store'), ['email' => 'admin@polrestts.test', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->operator()->update(['is_active' => false]);
        $this->post(route('admin.login.store'), ['email' => 'operator.lantas@polrestts.test', 'password' => 'password']);
        $this->assertGuest();
    }

    /** Semua halaman GET admin (index/create/edit) bisa dibuka Super Admin. */
    public function test_super_admin_can_open_all_admin_pages(): void
    {
        \App\Models\Slider::create(['judul' => 'S', 'gambar' => 'sliders/x.webp']);
        \App\Models\Video::create(['judul' => 'V', 'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ']);
        \App\Models\Document::create(['judul' => 'D', 'kategori' => 'berkala', 'path' => 'documents/x.pdf']);
        \App\Models\InformationRequest::create(['nama' => 'N', 'kontak' => 'K', 'informasi_diminta' => 'I', 'tujuan' => 'T']);

        $this->actingAs($this->super());
        $urls = $this->adminGetUrls();
        $this->assertGreaterThan(40, count($urls));
        foreach ($urls as $url) {
            $res = $this->get($url);
            $this->assertSame(200, $res->status(), "Gagal membuka $url (status {$res->status()})");
        }
    }

    public function test_admin_unit_is_blocked_from_super_admin_pages(): void
    {
        $this->actingAs($this->operator());
        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.officials.index'))->assertForbidden();
        $this->get(route('admin.units.create'))->assertForbidden();

        $own = $this->operator()->unit;
        $other = Unit::where('id', '!=', $own->id)->first();
        $this->get(route('admin.units.edit', $own))->assertOk();
        $this->get(route('admin.units.edit', $other))->assertForbidden();
        $this->put(route('admin.units.update', $other), ['deskripsi' => 'x'])->assertForbidden();
        $this->put(route('admin.units.update', $own), ['deskripsi' => 'Profil baru'])->assertRedirect();
        $this->assertSame('Profil baru', $own->fresh()->deskripsi);
    }

    private function adminGetUrls(): array
    {
        $urls = [];
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (! str_starts_with($uri, 'admin') || ! in_array('GET', $route->methods()) || $uri === 'admin/login') {
                continue;
            }
            $url = $this->fillParams($uri);
            if ($url && ! str_contains($url, '__SKIP__')) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /** Isi parameter route dengan record pertama dari tabel terkait. */
    private function fillParams(string $uri): ?string
    {
        return preg_replace_callback('/\{(\w+)\??\}/', function ($m) {
            $name = $m[1];
            if ($name === 'type') {
                return 'berita';
            }
            if ($name === 'post') {
                return \App\Models\Post::where('type', 'berita')->value('id');
            }
            $class = 'App\\Models\\'.str($name)->studly();
            if ($name === 'information_request') {
                $class = \App\Models\InformationRequest::class;
            }
            if ($name === 'complaint_channel') {
                $class = \App\Models\ComplaintChannel::class;
            }
            if (! class_exists($class) || ! ($model = $class::query()->first())) {
                return '__SKIP__';
            }

            return $model->getKey();
        }, '/'.$uri);
    }
}
