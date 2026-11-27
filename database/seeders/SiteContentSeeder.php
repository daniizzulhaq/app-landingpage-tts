<?php

namespace Database\Seeders;

use App\Models\ComplaintChannel;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    private const P = '(diisi admin)';

    public function run(): void
    {
        $settings = [
            'site_name' => 'Polres TTS',
            'site_tagline' => 'Kepolisian Resor Timor Tengah Selatan',
            'site_motto' => 'Diberkati untuk memberkati',
            'meta_description' => 'Situs resmi Kepolisian Resor Timor Tengah Selatan: informasi layanan, berita, kegiatan, dan informasi publik.',
            'alamat' => 'Alamat kantor '.self::P,
            'telepon' => null,
            'whatsapp' => null,
            'email' => null,
            'jam_pelayanan' => 'Jam pelayanan '.self::P,
            'require_approval' => '1',
            'ppid_standar_pelayanan' => '<p>Standar pelayanan PPID '.self::P.'.</p>',
        ];
        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        foreach (Page::KEYS as $key => $judul) {
            Page::firstOrCreate(['key' => $key], [
                'judul' => $judul,
                'isi' => '<p>'.$judul.' Polres Timor Tengah Selatan '.self::P.'.</p>',
            ]);
        }

        if (! ComplaintChannel::exists()) {
            ComplaintChannel::create([
                'nama' => 'Call Center Polri 110',
                'deskripsi' => 'Layanan darurat kepolisian 24 jam, bebas pulsa.',
                'url' => 'tel:110',
                'ikon' => 'bi-telephone',
                'urutan' => 1,
            ]);
            ComplaintChannel::create([
                'nama' => 'Kanal pengaduan resmi 1',
                'deskripsi' => 'Nama, deskripsi, dan tautan kanal '.self::P.'.',
                'url' => null,
                'ikon' => 'bi-globe',
                'urutan' => 2,
            ]);
            ComplaintChannel::create([
                'nama' => 'Kanal pengaduan resmi 2',
                'deskripsi' => 'Nama, deskripsi, dan tautan kanal '.self::P.'.',
                'url' => null,
                'ikon' => 'bi-whatsapp',
                'urutan' => 3,
            ]);
        }

        if (! Faq::exists()) {
            $faqs = [
                ['Berapa nomor darurat kepolisian?', '<p>Hubungi <strong>110</strong> untuk keadaan darurat. Layanan ini tersedia 24 jam dan bebas pulsa.</p>'],
                ['Di mana saya bisa melihat persyaratan layanan seperti SIM dan SKCK?', '<p>Persyaratan, prosedur, jadwal, dan lokasi setiap layanan tersedia di menu <strong>Layanan &gt; Informasi Layanan</strong>.</p>'],
                ['Bagaimana cara mengajukan permohonan informasi publik?', '<p>Isi form permohonan di halaman <strong>Informasi Publik &gt; PPID</strong>. Petugas akan menindaklanjuti melalui kontak yang Anda cantumkan.</p>'],
                ['Bagaimana cara menyampaikan pengaduan?', '<p>Gunakan kanal pengaduan resmi yang tercantum di halaman <strong>Layanan &gt; Pengaduan dan Bantuan</strong>.</p>'],
                ['Di mana lokasi Polsek terdekat?', '<p>Lihat halaman <strong>Profil &gt; Direktori Polsek</strong> untuk alamat, kontak, dan petunjuk arah setiap Polsek.</p>'],
            ];
            foreach ($faqs as $i => [$q, $a]) {
                Faq::create(['pertanyaan' => $q, 'jawaban' => $a, 'urutan' => $i + 1]);
            }
        }
    }
}
