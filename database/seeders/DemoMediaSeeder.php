<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Unit;
use App\Services\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * KHUSUS DEMO — mengisi logo + gambar dummy agar tampilan terlihat penuh.
 *
 * Logo diambil dari database/seeders/assets/logo-polres-tts.png.
 * Foto dummy diunduh dari Lorem Picsum (https://picsum.photos, foto Unsplash berlisensi bebas)
 * dan diproses seperti upload biasa (resize 1600px + thumbnail 600px).
 *
 * Jalankan:  php artisan db:seed --class=DemoMediaSeeder
 * Ganti semua gambar ini dengan foto resmi sebelum situs dipublikasikan.
 */
class DemoMediaSeeder extends Seeder
{
    private ImageService $images;

    private string $tmp;

    public function run(): void
    {
        $this->images = app(ImageService::class);
        $this->tmp = storage_path('app/demo-tmp');
        File::ensureDirectoryExists($this->tmp);

        $this->logo();
        $this->sliders();
        $this->posts();
        $this->albums();
        $this->units();
        $this->pages();

        File::deleteDirectory($this->tmp);
        $this->command?->info('Gambar demo selesai dipasang.');
    }

    private function logo(): void
    {
        $source = database_path('seeders/assets/logo-polres-tts.png');
        if (! File::exists($source)) {
            $this->command?->warn('Logo tidak ditemukan: '.$source);

            return;
        }
        Storage::disk('public')->delete((string) setting('logo'));
        Storage::disk('public')->put('settings/logo-polres-tts.png', File::get($source));
        Setting::put('logo', 'settings/logo-polres-tts.png');
        $this->command?->line('  ✓ Logo');
    }

    private function sliders(): void
    {
        $slides = [
            ['Melayani dengan Sepenuh Hati', 'Informasi layanan SIM, SKCK, izin keramaian, dan surat kehilangan dalam satu tempat.', '/layanan'],
            ['Bersama Menjaga Kamtibmas Timor Tengah Selatan', 'Sampaikan informasi dan pengaduan melalui kanal resmi. Darurat? Hubungi 110.', '/pengaduan'],
            ['Kabar Terkini dari Polres TTS', 'Ikuti berita, kegiatan, dan agenda terbaru kepolisian di wilayah Anda.', '/berita'],
        ];
        foreach ($slides as $i => [$judul, $sub, $link]) {
            $old = Slider::where('judul', $judul)->first();
            if ($old) {
                $this->images->delete($old->gambar);
                $old->delete();
            }
            $path = $this->fetch("slider-$i", 1920, 1080, 'sliders');
            if ($path) {
                Slider::create(['judul' => $judul, 'subjudul' => $sub, 'link' => $link, 'gambar' => $path, 'urutan' => $i + 1, 'is_active' => true]);
            }
        }
        if ($first = Slider::orderBy('urutan')->first()) {
            Setting::put('og_image', $first->gambar);
        }
        $this->command?->line('  ✓ Slider');
    }

    private function posts(): void
    {
        $posts = Post::orderBy('id')->get();
        foreach ($posts as $post) {
            $this->images->delete($post->thumbnail);
            $post->thumbnail = $this->fetch('post-'.$post->id, 1600, 1000, 'posts');

            // Edukasi: poster/infografis potret agar fitur perbesar & unduh terlihat
            if ($post->type === Post::TYPE_EDUKASI) {
                $this->images->delete($post->lampiran);
                $post->lampiran = $this->fetch('poster-'.$post->id, 1080, 1350, 'posts/lampiran');
            }
            $post->save();

            // Galeri foto untuk beberapa berita & kegiatan
            if (in_array($post->type, [Post::TYPE_BERITA, Post::TYPE_KEGIATAN]) && $post->images()->count() === 0 && $post->id % 2 === 1) {
                foreach (range(1, 4) as $n) {
                    if ($path = $this->fetch("post-{$post->id}-g$n", 1600, 1066, 'posts/galeri')) {
                        $post->images()->create(['path' => $path, 'caption' => 'Dokumentasi kegiatan (foto contoh) '.$n]);
                    }
                }
            }
        }
        $this->command?->line('  ✓ Gambar konten ('.$posts->count().' post)');
    }

    private function albums(): void
    {
        $admin = \App\Models\User::where('email', 'admin@polrestts.test')->first();
        $definitions = [
            'Contoh Album Kegiatan' => 9,
            'Contoh Album Patroli dan Pelayanan' => 6,
            'Contoh Album Bakti Sosial' => 6,
            'Contoh Album Upacara dan Apel' => 6,
        ];
        foreach ($definitions as $judul => $count) {
            $album = Album::firstOrCreate(['judul' => $judul], [
                'user_id' => $admin?->id, 'deskripsi' => 'Album contoh berisi foto dummy untuk demo.',
                'tanggal' => now()->subDays(random_int(1, 30))->toDateString(),
                'status' => 'published', 'published_at' => now(), 'approved_by' => $admin?->id,
            ]);
            foreach ($album->photos as $photo) {
                $this->images->delete($photo->path);
                $photo->delete();
            }
            foreach (range(1, $count) as $n) {
                if ($path = $this->fetch("album-{$album->id}-$n", 1600, $n % 3 === 0 ? 1200 : 1066, 'albums/'.$album->id)) {
                    $album->photos()->create(['path' => $path, 'caption' => 'Foto contoh '.$n, 'urutan' => $n]);
                }
            }
        }
        $this->command?->line('  ✓ Album foto');
    }

    private function units(): void
    {
        $units = Unit::where('type', 'polsek')->orWhereIn('slug', ['sat-lantas', 'sat-samapta', 'sat-binmas', 'sat-reskrim', 'spkt'])->get();
        foreach ($units as $unit) {
            $this->images->delete($unit->foto);
            $unit->update(['foto' => $this->fetch('unit-'.$unit->id, 1600, 1000, 'units')]);
        }
        $this->command?->line('  ✓ Foto unit/polsek');
    }

    private function pages(): void
    {
        foreach (['sejarah', 'wilayah_hukum'] as $key) {
            if ($page = Page::byKey($key)) {
                $this->images->delete($page->gambar);
                $page->update(['gambar' => $this->fetch('page-'.$key, 1600, 900, 'pages')]);
            }
        }
        $this->command?->line('  ✓ Gambar halaman profil');
    }

    /** Unduh satu foto dummy (seed tetap = gambar sama tiap dijalankan) lalu proses lewat ImageService. */
    private function fetch(string $seed, int $w, int $h, string $dir): ?string
    {
        try {
            $res = Http::timeout(40)->retry(2, 1000)->get("https://picsum.photos/seed/polrestts-$seed/$w/$h.jpg");
            if (! $res->successful() || ! str_starts_with((string) $res->header('Content-Type'), 'image/')) {
                return null;
            }
            $file = $this->tmp.'/'.$seed.'.jpg';
            File::put($file, $res->body());

            return $this->images->store(new UploadedFile($file, $seed.'.jpg', 'image/jpeg', null, true), $dir);
        } catch (\Throwable $e) {
            $this->command?->warn("  Gagal mengunduh $seed: ".$e->getMessage());

            return null;
        }
    }
}
