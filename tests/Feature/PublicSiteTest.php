<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Album;
use App\Models\Document;
use App\Models\InformationRequest;
use App\Models\Photo;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Unit;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_all_public_pages_render(): void
    {
        $post = fn ($type) => Post::published()->where('type', $type)->first();
        $urls = [
            route('home'),
            route('site.profile'), route('site.leaders'), route('site.structure'),
            route('site.units.index'), route('site.units.show', Unit::where('type', 'sat')->first()->slug),
            route('site.units.show', Unit::where('type', 'polsek')->first()->slug),
            route('site.polsek'), route('site.polsek', ['q' => 'Contoh 2']),
            route('site.services.index'), route('site.services.show', Service::first()->slug),
            route('site.complaints'), route('site.faq'),
            route('site.agenda'), route('site.agenda', ['arsip' => 1]),
            route('site.gallery.photos'), route('site.gallery.videos'),
            route('site.ppid'), route('site.contact'),
            route('site.search'), route('site.search', ['q' => 'contoh']), route('site.search', ['q' => 'zzzzqqq']),
        ];
        foreach (array_keys(Post::TYPES) as $type) {
            $urls[] = route('site.posts.index', $type);
            $urls[] = route('site.posts.index', [$type, 'kategori' => 'kamtibmas', 'q' => 'contoh']);
            $urls[] = $post($type)->url();
        }

        foreach ($urls as $url) {
            $res = $this->get($url);
            $this->assertSame(200, $res->status(), "Gagal: $url");
        }
    }

    public function test_home_shows_content_with_full_data(): void
    {
        // Lengkapi data yang tidak ada di seeder (slider, video, foto album, dokumen)
        Slider::create(['judul' => 'Slide Uji', 'gambar' => 'sliders/x.webp', 'is_active' => true]);
        Video::create(['judul' => 'Video Uji', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $album = Album::first();
        Photo::create(['album_id' => $album->id, 'path' => 'albums/x.webp', 'caption' => 'Keterangan uji']);
        $post = Post::published()->where('type', 'berita')->first();
        PostImage::create(['post_id' => $post->id, 'path' => 'posts/galeri/x.webp', 'caption' => 'Foto galeri uji']);
        Document::create(['judul' => 'Dokumen Uji', 'kategori' => 'berkala', 'path' => 'documents/x.pdf', 'tahun' => 2026]);

        $this->get(route('home'))->assertOk()->assertSee('Slide Uji')->assertSee('Video Uji')
            ->assertSee('Contoh Pengumuman: Jadwal Pelayanan')
            ->assertSee('Berita terbaru')->assertSee('Agenda')->assertSee('Edukasi kamtibmas')->assertSee('Galeri');
        $this->get(route('site.gallery.album', $album->slug))->assertOk()->assertSee('Keterangan uji');
        $this->get(route('site.gallery.videos'))->assertOk()->assertSee('youtube-nocookie.com/embed/dQw4w9WgXcQ', false);
        $this->get($post->url())->assertOk()->assertSee('Foto galeri uji')->assertSee('wa.me', false);
        $this->get(route('site.ppid'))->assertOk()->assertSee('Dokumen Uji');
        $this->get(route('site.search', ['q' => 'Dokumen Uji']))->assertOk()->assertSee('Dokumen PPID');
    }

    public function test_unpublished_content_is_hidden(): void
    {
        $pending = Post::where('status', 'pending')->first();
        $this->get(route('site.posts.show', [$pending->type, $pending->slug]))->assertNotFound();
        $this->get(route('site.posts.index', 'berita'))->assertDontSee($pending->judul);
        $this->get(route('site.search', ['q' => 'Menunggu Persetujuan']))->assertDontSee($pending->judul);

        $future = Post::published()->where('type', 'berita')->first();
        $future->update(['published_at' => now()->addDays(3)]);
        $this->get($future->url())->assertNotFound();

        $agenda = Agenda::first();
        $agenda->update(['status' => 'draft']);
        $this->get(route('site.agenda', ['arsip' => $agenda->mulai->isPast() ? 1 : 0]))->assertDontSee($agenda->judul);

        $album = Album::first();
        Photo::create(['album_id' => $album->id, 'path' => 'albums/x.webp']);
        $album->update(['status' => 'draft']);
        $this->get(route('site.gallery.album', $album->slug))->assertNotFound();
    }

    public function test_post_views_counted_once_per_session(): void
    {
        $post = Post::published()->where('type', 'berita')->first();
        $before = $post->views;
        $this->get($post->url());
        $this->get($post->url());
        $this->assertSame($before + 1, $post->fresh()->views);
    }

    public function test_wrong_type_in_url_is_404(): void
    {
        $post = Post::published()->where('type', 'berita')->first();
        $this->get('/kegiatan/'.$post->slug)->assertNotFound();
        $this->get('/tidak-ada')->assertNotFound();
    }

    public function test_ppid_request_form(): void
    {
        $data = ['nama' => 'Warga Uji', 'kontak' => '08123456789', 'informasi_diminta' => 'Data statistik layanan SKCK tahun lalu', 'tujuan' => 'Penelitian skripsi'];

        $this->post(route('site.ppid.store'), $data)->assertRedirect();
        $this->assertDatabaseHas('information_requests', ['nama' => 'Warga Uji', 'status' => 'baru']);

        // Honeypot terisi -> tidak disimpan
        $this->post(route('site.ppid.store'), $data + ['website' => 'http://spam.test'])->assertRedirect();
        $this->assertSame(1, InformationRequest::count());

        // Validasi
        $this->post(route('site.ppid.store'), ['nama' => ''])->assertSessionHasErrors(['nama', 'kontak', 'informasi_diminta', 'tujuan']);
    }

    public function test_ppid_form_is_throttled(): void
    {
        $data = ['nama' => 'A', 'kontak' => 'B', 'informasi_diminta' => 'Informasi yang cukup panjang', 'tujuan' => 'Tujuan uji'];
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('site.ppid.store'), $data)->assertRedirect();
        }
        $this->post(route('site.ppid.store'), $data)->assertStatus(429);
    }

    public function test_seo_meta_present(): void
    {
        $post = Post::published()->where('type', 'berita')->first();
        $this->get($post->url())
            ->assertSee('<meta property="og:title" content="'.e($post->judul).'">', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('"@type":"NewsArticle"', false);
    }
}
