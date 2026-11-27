<?php

namespace Tests\Feature;

use App\Models\Official;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KapolresProfileTest extends TestCase
{
    public function test_demo_names_use_poliran_profile_without_changing_saved_data(): void
    {
        foreach ([null, '(diisi admin)', 'Contoh Nama Kapolres, S.I.K., M.H.'] as $name) {
            $original = new Official(['level' => 'kapolres', 'nama' => $name, 'tugas' => 'Demo riwayat']);
            $display = $original->forDisplay();

            $this->assertSame('AKBP Kristiyan Beorbel Martino, S.H., S.I.K.,M.M. C.ELM.', $display->namaLengkap());
            $this->assertNull($display->tugas);
            $this->assertSame($name, $original->nama);
        }
    }

    public function test_real_official_profile_and_other_officials_are_preserved(): void
    {
        foreach (['kapolres', 'wakapolres'] as $level) {
            $official = new Official(['level' => $level, 'nama' => 'Nama dari admin', 'pangkat' => 'AKBP', 'tugas' => 'Profil resmi']);

            $this->assertSame($official->getAttributes(), $official->forDisplay()->getAttributes());
        }
    }

    public function test_photo_works_without_storage_and_keeps_admin_uploads(): void
    {
        Storage::fake('public');
        $official = new Official(['level' => 'kapolres']);
        $this->assertSame(asset('img/kapolres-tts.jpg'), $official->fotoUrl());
        $this->assertFileExists(public_path('img/kapolres-tts.jpg'));

        Storage::disk('public')->put('officials/custom.webp', 'custom photo');
        $official->foto = 'officials/custom.webp';
        $this->assertSame(asset('storage/officials/custom.webp'), $official->fotoUrl());
    }

    public function test_demo_welcome_uses_poliran_text_without_changing_original(): void
    {
        $page = new Page(['key' => 'sambutan_kapolres', 'isi' => '<p>Catatan: teks ini adalah contoh untuk demo.</p>']);
        $display = $page->forDisplay();

        $this->assertStringContainsString('Selamat datang di POLIRAN.', $display->isi);
        $this->assertStringContainsString('pelatihan dan disalurkan ke pekerjaan yang layak.', $display->isi);
        $this->assertStringContainsString('contoh untuk demo', $page->isi);
    }

    public function test_admin_welcome_and_other_pages_are_preserved(): void
    {
        foreach (['sambutan_kapolres', 'sejarah'] as $key) {
            $page = new Page(['key' => $key, 'judul' => 'Judul admin', 'isi' => '<p>Teks resmi dari admin.</p>']);
            $this->assertSame($page->getAttributes(), $page->forDisplay()->getAttributes());
        }
    }
}
