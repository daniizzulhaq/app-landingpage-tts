<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Category;
use App\Models\Post;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Konten contoh agar tampilan tidak kosong.
 * Semua teks bertanda "Contoh" dan harus diganti/dihapus admin sebelum situs dipublikasikan.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Post::exists()) {
            return;
        }

        $categories = collect(['Kamtibmas', 'Lalu Lintas', 'Pelayanan Publik', 'Pembinaan Masyarakat', 'Internal'])
            ->mapWithKeys(fn ($name) => [$name => Category::firstOrCreate(['name' => $name])->id]);

        $admin = User::where('email', 'admin@polrestts.test')->first();
        $operator = User::where('email', 'operator.lantas@polrestts.test')->first();
        $unit = fn (string $slug) => Unit::where('slug', $slug)->value('id');

        $body = fn (string $topik) => '<p><em>Ini adalah konten contoh. Ganti dengan '.$topik.' resmi melalui panel admin.</em></p>'
            .'<p>Paragraf pembuka berisi ringkasan peristiwa: apa, siapa, kapan, dan di mana. Tuliskan informasi secara singkat, jelas, dan sesuai fakta.</p>'
            .'<h3>Rincian</h3><p>Paragraf berikutnya menjelaskan rincian kegiatan, pihak yang terlibat, serta pesan kamtibmas yang ingin disampaikan kepada masyarakat.</p>'
            .'<ul><li>Poin informasi pertama</li><li>Poin informasi kedua</li><li>Poin informasi ketiga</li></ul>';

        $berita = [
            ['Contoh Berita: Patroli Dialogis Jaga Situasi Kamtibmas Tetap Kondusif', 'Kamtibmas', 'sat-samapta'],
            ['Contoh Berita: Operasi Keselamatan Lalu Lintas Mengutamakan Edukasi', 'Lalu Lintas', 'sat-lantas'],
            ['Contoh Berita: Pelayanan SKCK Kini Lebih Tertib dan Cepat', 'Pelayanan Publik', 'sat-intelkam'],
            ['Contoh Berita: Sambang Desa Bersama Bhabinkamtibmas', 'Pembinaan Masyarakat', 'sat-binmas'],
            ['Contoh Berita: Penyuluhan Bahaya Narkoba di Sekolah', 'Pembinaan Masyarakat', 'sat-resnarkoba'],
            ['Contoh Berita: Apel Kesiapan Pengamanan Hari Besar Keagamaan', 'Internal', 'bag-ops'],
            ['Contoh Siaran Pers: Imbauan Waspada Penipuan Daring', 'Kamtibmas', 'sie-humas'],
        ];
        foreach ($berita as $i => [$judul, $cat, $slug]) {
            $this->post('berita', $judul, $categories[$cat], $unit($slug), $admin, $body('berita'), now()->subDays($i * 2 + 1));
        }

        $kegiatan = [
            ['Contoh Kegiatan: Pengaturan Arus Lalu Lintas Pagi Hari', 'Lalu Lintas', 'sat-lantas', 'Ruas jalan utama kota (contoh)'],
            ['Contoh Kegiatan: Bakti Sosial Polres untuk Warga', 'Pembinaan Masyarakat', 'sat-binmas', 'Balai desa (contoh)'],
            ['Contoh Kegiatan: Pemeriksaan Kesehatan Personel', 'Internal', 'sie-dokkes', 'Mapolres (contoh)'],
            ['Contoh Kegiatan: Patroli Malam Wilayah Rawan', 'Kamtibmas', 'sat-samapta', 'Wilayah kota (contoh)'],
        ];
        foreach ($kegiatan as $i => [$judul, $cat, $slug, $lokasi]) {
            $p = $this->post('kegiatan', $judul, $categories[$cat], $unit($slug), $admin, $body('laporan kegiatan'), now()->subDays($i * 3 + 1));
            $p->update(['lokasi' => $lokasi, 'tanggal_kegiatan' => now()->subDays($i * 3 + 1)->toDateString()]);
        }

        $pengumuman = [
            ['Contoh Pengumuman: Jadwal Pelayanan pada Hari Libur Nasional', true],
            ['Contoh Pengumuman: Penerimaan Anggota Polri (diisi admin)', false],
        ];
        foreach ($pengumuman as $i => [$judul, $penting]) {
            $p = $this->post('pengumuman', $judul, $categories['Pelayanan Publik'], null, $admin, $body('pengumuman'), now()->subDays($i + 1));
            $p->update(['is_penting' => $penting]);
        }

        $edukasi = [
            ['Contoh Edukasi: Tips Aman Berkendara Roda Dua', 'Lalu Lintas', 'sat-lantas'],
            ['Contoh Edukasi: Kenali Modus Penipuan Lewat Telepon', 'Kamtibmas', 'sat-reskrim'],
            ['Contoh Edukasi: Cegah Bullying di Lingkungan Sekolah', 'Pembinaan Masyarakat', 'sat-binmas'],
        ];
        foreach ($edukasi as $i => [$judul, $cat, $slug]) {
            $this->post('edukasi', $judul, $categories[$cat], $unit($slug), $admin, $body('materi edukasi'), now()->subDays($i * 4 + 2));
        }

        // Contoh alur approval: satu konten pending dari Admin Unit.
        if ($operator) {
            Post::create([
                'type' => 'berita', 'unit_id' => $operator->unit_id, 'user_id' => $operator->id,
                'category_id' => $categories['Lalu Lintas'],
                'judul' => 'Contoh Berita Menunggu Persetujuan dari Admin Unit',
                'ringkasan' => 'Contoh konten berstatus pending untuk menguji menu Menunggu Approval.',
                'isi' => $body('berita'), 'status' => 'pending',
            ]);
        }

        Album::create([
            'unit_id' => null, 'user_id' => $admin?->id, 'judul' => 'Contoh Album Kegiatan',
            'deskripsi' => 'Album contoh. Unggah foto melalui panel admin.', 'tanggal' => now()->toDateString(),
            'status' => 'published', 'published_at' => now(), 'approved_by' => $admin?->id,
        ]);
    }

    private function post(string $type, string $judul, ?int $categoryId, ?int $unitId, ?User $admin, string $isi, $date): Post
    {
        return Post::create([
            'type' => $type,
            'unit_id' => $unitId,
            'user_id' => $admin?->id,
            'category_id' => $categoryId,
            'judul' => $judul,
            'ringkasan' => 'Ringkasan contoh. Ganti dengan ringkasan singkat 1–2 kalimat yang menjelaskan isi konten.',
            'isi' => $isi,
            'status' => 'published',
            'approved_by' => $admin?->id,
            'published_at' => $date,
            'views' => random_int(5, 300),
        ]);
    }
}
