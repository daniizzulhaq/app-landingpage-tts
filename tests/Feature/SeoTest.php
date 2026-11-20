<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_sitemap_lists_published_only(): void
    {
        $published = Post::published()->first();
        $pending = Post::where('status', 'pending')->first();

        $res = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $res->assertSee($published->url(), false)->assertDontSee($pending->slug, false);
        $this->assertNotFalse(simplexml_load_string($res->getContent()), 'sitemap.xml bukan XML valid');
    }

    public function test_robots_txt(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('User-agent: *');
        app()->detectEnvironment(fn () => 'production');
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Sitemap: ');
    }

    public function test_custom_404(): void
    {
        $this->get('/berita/tidak-ada-slug')->assertNotFound()->assertSee('Halaman tidak ditemukan');
    }
}
