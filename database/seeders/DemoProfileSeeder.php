<?php

namespace Database\Seeders;

use App\Models\Official;
use App\Models\Page;
use App\Models\Unit;
use Illuminate\Database\Seeder;

/**
 * KHUSUS DEMO — mengisi profil Polres, pimpinan, pejabat, dan profil unit dengan teks CONTOH.
 *
 * - Nama pejabat adalah nama FIKTIF (bukan pejabat sebenarnya).
 * - Sejarah, visi misi, dan sambutan adalah teks contoh.
 * - Tugas & fungsi mengacu UU No. 2 Tahun 2002 tentang Kepolisian Negara RI.
 *
 * Jalankan:  php artisan db:seed --class=DemoProfileSeeder
 * WAJIB diganti data resmi sebelum situs dipublikasikan.
 */
class DemoProfileSeeder extends Seeder
{
    private const NOTE = '<p><em>Catatan: teks ini adalah contoh untuk demo dan perlu diganti dengan data resmi.</em></p>';

    public function run(): void
    {
        $this->pages();
        $this->leaders();
        $this->units();
        $this->command?->info('Profil contoh selesai diisi.');
    }

    private function pages(): void
    {
        $pages = [
            'sejarah' => ['Sejarah', self::NOTE.<<<'HTML'
<p>Kepolisian Resor Timor Tengah Selatan (Polres TTS) merupakan satuan kewilayahan di bawah Kepolisian Daerah Nusa Tenggara Timur yang berkedudukan di Kota SoE, ibu kota Kabupaten Timor Tengah Selatan.</p>
<p>Sejak awal berdirinya, Polres TTS tumbuh seiring perkembangan masyarakat di wilayah tengah Pulau Timor. Pada masa-masa awal, pelayanan kepolisian dilaksanakan dengan sarana yang terbatas, menjangkau desa-desa di wilayah pegunungan dan pesisir selatan dengan medan yang menantang.</p>
<h3>Perkembangan Organisasi</h3>
<p>Seiring bertambahnya jumlah penduduk dan kebutuhan pelayanan, organisasi Polres TTS terus diperkuat. Pembentukan Polsek-Polsek jajaran memperpendek jarak pelayanan bagi masyarakat di tingkat kecamatan, sementara satuan fungsi seperti lalu lintas, reserse, intelijen, dan pembinaan masyarakat dikembangkan untuk menjawab tantangan keamanan yang semakin beragam.</p>
<h3>Polres TTS Saat Ini</h3>
<p>Kini Polres TTS terus berbenah melalui transformasi pelayanan publik berbasis teknologi informasi, peningkatan profesionalisme personel, serta penguatan kemitraan dengan pemerintah daerah, tokoh adat, tokoh agama, dan seluruh elemen masyarakat untuk mewujudkan situasi kamtibmas yang aman dan kondusif.</p>
HTML],
            'visi_misi' => ['Visi dan Misi', self::NOTE.<<<'HTML'
<h3>Visi</h3>
<blockquote>Terwujudnya pelayanan kepolisian yang profesional, modern, dan terpercaya dalam memelihara keamanan dan ketertiban masyarakat di wilayah Kabupaten Timor Tengah Selatan.</blockquote>
<h3>Misi</h3>
<ol>
<li>Memberikan perlindungan, pengayoman, dan pelayanan kepada masyarakat secara cepat, tepat, transparan, dan akuntabel.</li>
<li>Melaksanakan deteksi dini dan pencegahan gangguan kamtibmas melalui kegiatan intelijen dan pemolisian masyarakat.</li>
<li>Menegakkan hukum secara profesional, objektif, dan berkeadilan dengan menjunjung tinggi hak asasi manusia.</li>
<li>Mewujudkan keamanan, keselamatan, ketertiban, dan kelancaran lalu lintas.</li>
<li>Meningkatkan kualitas sumber daya manusia Polri yang berintegritas serta memanfaatkan teknologi informasi dalam pelayanan.</li>
<li>Membangun sinergi dengan pemerintah daerah, TNI, tokoh adat, tokoh agama, dan seluruh komponen masyarakat.</li>
</ol>
HTML],
            'tugas_fungsi' => ['Tugas dan Fungsi', <<<'HTML'
<h3>Tugas Pokok</h3>
<p>Sesuai Pasal 13 Undang-Undang Nomor 2 Tahun 2002 tentang Kepolisian Negara Republik Indonesia, tugas pokok Polri adalah:</p>
<ol>
<li>Memelihara keamanan dan ketertiban masyarakat;</li>
<li>Menegakkan hukum; dan</li>
<li>Memberikan perlindungan, pengayoman, dan pelayanan kepada masyarakat.</li>
</ol>
<h3>Fungsi</h3>
<p>Dalam melaksanakan tugas pokok tersebut, Polres TTS menyelenggarakan fungsi antara lain:</p>
<ul>
<li>Pelayanan kepolisian kepada masyarakat, termasuk penerimaan dan penanganan laporan/pengaduan serta pelayanan surat-surat kepolisian;</li>
<li>Intelijen keamanan, termasuk deteksi dini dan pelayanan perizinan/pemberitahuan kegiatan masyarakat;</li>
<li>Penyelidikan dan penyidikan tindak pidana, termasuk tindak pidana narkoba;</li>
<li>Pembinaan masyarakat dan pemolisian masyarakat;</li>
<li>Kegiatan patroli, pengaturan, penjagaan, dan pengawalan (Turjawali) oleh fungsi Samapta;</li>
<li>Penyelenggaraan fungsi lalu lintas, termasuk penegakan hukum, registrasi, dan identifikasi pengemudi;</li>
<li>Pengawasan, pembinaan profesi, dan pengamanan internal;</li>
<li>Dukungan manajemen: perencanaan, sumber daya manusia, logistik, keuangan, hukum, teknologi informasi, kesehatan, dan hubungan masyarakat.</li>
</ul>
HTML],
            'wilayah_hukum' => ['Wilayah Hukum', self::NOTE.<<<'HTML'
<p>Wilayah hukum Polres Timor Tengah Selatan meliputi seluruh wilayah administratif Kabupaten Timor Tengah Selatan, Provinsi Nusa Tenggara Timur, yang terletak di bagian tengah Pulau Timor dengan ibu kota di Kota SoE.</p>
<p>Wilayah ini memiliki karakteristik geografis yang beragam, mulai dari daerah pegunungan dan dataran tinggi hingga kawasan pesisir di bagian selatan. Kondisi tersebut menuntut pola pelayanan kepolisian yang mampu menjangkau masyarakat hingga ke desa-desa terpencil.</p>
<h3>Pembagian Wilayah Pelayanan</h3>
<p>Pelayanan kepolisian di tingkat kecamatan dilaksanakan oleh Polsek-Polsek jajaran. Daftar Polsek beserta wilayah pelayanannya dapat dilihat pada halaman <a href="/polsek">Direktori Polsek</a>.</p>
HTML],
            'sambutan_kapolres' => ['Sambutan Kapolres', self::NOTE.<<<'HTML'
<p><strong>Salam sejahtera bagi kita semua,</strong></p>
<p>Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas hadirnya website resmi Polres Timor Tengah Selatan. Website ini kami hadirkan sebagai sarana informasi dan komunikasi antara Polres TTS dengan masyarakat.</p>
<p>Melalui website ini, masyarakat dapat memperoleh informasi mengenai persyaratan dan prosedur layanan kepolisian, berita dan kegiatan terkini, agenda, serta informasi publik secara terbuka. Kami juga menyediakan kanal pengaduan resmi sebagai wujud komitmen kami untuk terus memperbaiki kualitas pelayanan.</p>
<p>Kami menyadari bahwa keamanan dan ketertiban tidak dapat diwujudkan oleh Polri sendiri. Karena itu, kami mengajak seluruh masyarakat Timor Tengah Selatan untuk bersama-sama menjaga kamtibmas di lingkungan masing-masing. Jangan ragu untuk menghubungi layanan darurat <strong>110</strong> apabila membutuhkan bantuan kepolisian.</p>
<p>Terima kasih atas kepercayaan dan dukungan masyarakat. Mari bersama kita wujudkan Timor Tengah Selatan yang aman, tertib, dan sejahtera.</p>
HTML],
        ];

        foreach ($pages as $key => [$judul, $isi]) {
            Page::updateOrCreate(['key' => $key], ['judul' => $judul, 'isi' => $isi]);
        }
        $this->command?->line('  ✓ Halaman profil (sejarah, visi misi, tugas & fungsi, wilayah hukum, sambutan)');
    }

    private function leaders(): void
    {
        Official::updateOrCreate(['level' => 'kapolres'], [
            'nama' => 'Contoh Nama Kapolres, S.I.K., M.H.',
            'pangkat' => 'AKBP',
            'jabatan' => 'Kapolres Timor Tengah Selatan',
            'urutan' => 1,
            'tugas' => self::NOTE.'<p>Kapolres merupakan pimpinan Polres yang bertanggung jawab kepada Kapolda dalam penyelenggaraan tugas pokok Polri di wilayah hukum Polres Timor Tengah Selatan.</p>'
                .'<h4>Riwayat Jabatan (contoh)</h4><ul><li>Kasat Reskrim Polres (contoh)</li><li>Kabag Ops Polres (contoh)</li><li>Wakapolres (contoh)</li><li>Kapolres Timor Tengah Selatan</li></ul>'
                .'<h4>Pendidikan (contoh)</h4><ul><li>Akademi Kepolisian</li><li>Sekolah Tinggi Ilmu Kepolisian (S.I.K.)</li><li>Magister Hukum (M.H.)</li></ul>',
        ]);

        Official::updateOrCreate(['level' => 'wakapolres'], [
            'nama' => 'Contoh Nama Wakapolres, S.H.',
            'pangkat' => 'Kompol',
            'jabatan' => 'Wakapolres Timor Tengah Selatan',
            'urutan' => 2,
            'tugas' => self::NOTE.'<p>Wakapolres adalah unsur pimpinan yang membantu Kapolres dalam melaksanakan tugasnya dengan mengendalikan pelaksanaan tugas staf seluruh satuan organisasi di lingkungan Polres, serta memimpin Polres apabila Kapolres berhalangan.</p>'
                .'<h4>Tugas Wakapolres</h4><ul><li>Membantu Kapolres dalam perencanaan, pengendalian, dan pengawasan pelaksanaan tugas.</li><li>Mengoordinasikan pelaksanaan tugas bagian, satuan fungsi, seksi, dan Polsek.</li><li>Memberikan saran pertimbangan kepada Kapolres.</li></ul>',
        ]);
        $this->command?->line('  ✓ Kapolres & Wakapolres (nama fiktif)');
    }

    private function units(): void
    {
        // Nama fiktif untuk demo.
        $names = ['Yohanes Taneo', 'Markus Nenobais', 'Agustinus Benu', 'Fransiskus Tefa', 'Daniel Selan', 'Yosef Nubatonis',
            'Petrus Liu', 'Maria Banunaek', 'Stefanus Boimau', 'Paulus Kase', 'Antonius Mella', 'Elisabeth Tanesib',
            'Lukas Faot', 'Kristofel Sanam', 'Benediktus Nope', 'Theresia Bilaut', 'Aleksander Neolaka', 'Simon Tse',
            'Robertus Asbanu', 'Imanuel Oematan', 'Gabriel Tamonob', 'Rafael Nuban', 'Herman Toto'];

        $profiles = [
            'bag-ops' => ['Perencanaan, pengendalian, dan administrasi operasi kepolisian, termasuk pengamanan kegiatan masyarakat dan pemerintahan.', 'Pengamanan kegiatan masyarakat;Pengendalian situasi kamtibmas;Pelayanan pengaduan terkait operasional'],
            'bag-ren' => ['Penyusunan perencanaan program kerja dan anggaran, serta pengendalian dan evaluasi kinerja Polres.', 'Penyusunan rencana kerja;Evaluasi kinerja satuan'],
            'bag-sdm' => ['Pembinaan dan administrasi sumber daya manusia, termasuk pembinaan karier, kesejahteraan, dan perawatan personel.', 'Administrasi personel;Pembinaan karier dan kesejahteraan personel'],
            'bag-log' => ['Pembinaan logistik: pengadaan, pemeliharaan, dan perawatan sarana prasarana serta materiil Polres.', 'Pengelolaan sarana prasarana;Pemeliharaan materiil dan fasilitas'],
            'sat-intelkam' => ['Penyelenggaraan fungsi intelijen keamanan untuk deteksi dini gangguan kamtibmas, serta pelayanan administrasi perizinan dan SKCK.', 'Penerbitan SKCK;Pemberitahuan dan izin keramaian;Pelayanan izin senjata api olahraga dan bahan peledak sesuai ketentuan'],
            'sat-reskrim' => ['Penyelidikan dan penyidikan tindak pidana umum dan tertentu, termasuk identifikasi dan pembinaan fungsi reserse.', 'Penerimaan laporan tindak pidana;Penyidikan perkara pidana;Pemberian SP2HP kepada pelapor'],
            'sat-resnarkoba' => ['Penyelidikan dan penyidikan tindak pidana penyalahgunaan dan peredaran gelap narkoba, serta pencegahan melalui penyuluhan.', 'Penerimaan informasi peredaran narkoba;Penyuluhan bahaya narkoba'],
            'sat-lantas' => ['Penyelenggaraan fungsi lalu lintas: pengaturan, penjagaan, pengawalan, patroli, pendidikan masyarakat, registrasi dan identifikasi pengemudi, serta penegakan hukum lalu lintas.', 'Penerbitan dan perpanjangan SIM;Penanganan kecelakaan lalu lintas;Pengawalan dan pengaturan lalu lintas;Edukasi keselamatan berlalu lintas'],
            'sat-samapta' => ['Penyelenggaraan kegiatan patroli, pengaturan, penjagaan, dan pengawalan (Turjawali), pengendalian massa, serta pertolongan dan penyelamatan.', 'Patroli wilayah;Pengamanan objek vital dan kegiatan masyarakat;Pertolongan dan penyelamatan (SAR)'],
            'sat-binmas' => ['Pembinaan masyarakat melalui pemolisian masyarakat (Polmas), penyuluhan, pembinaan Bhabinkamtibmas, serta kerja sama dengan instansi dan tokoh masyarakat.', 'Penyuluhan kamtibmas;Pembinaan Bhabinkamtibmas;Pembinaan satpam dan pengamanan swakarsa'],
            'sat-tahti' => ['Perawatan tahanan, termasuk kesehatan tahanan, serta pengelolaan barang bukti.', 'Pelayanan kunjungan tahanan sesuai jadwal;Penitipan barang untuk tahanan'],
            'spkt' => ['Pelayanan kepolisian terpadu: penerimaan dan penanganan pertama laporan/pengaduan masyarakat, serta pelayanan bantuan kepolisian.', 'Penerimaan laporan dan pengaduan 24 jam;Surat keterangan tanda lapor kehilangan;Pelayanan bantuan dan informasi'],
            'sie-propam' => ['Pembinaan dan pemeliharaan disiplin, pengamanan internal, pelayanan pengaduan masyarakat terhadap perilaku anggota, serta penegakan disiplin dan kode etik.', 'Pengaduan perilaku anggota Polri;Pengawasan disiplin dan kode etik'],
            'sie-humas' => ['Pengelolaan informasi dan dokumentasi, hubungan masyarakat, serta pelayanan informasi publik (PPID).', 'Pelayanan informasi publik;Siaran pers dan publikasi;Pengelolaan website dan media sosial'],
            'sie-hukum' => ['Penyuluhan dan bantuan hukum, serta penanganan perkara praperadilan dan gugatan.', 'Penyuluhan hukum;Bantuan dan nasihat hukum bagi personel'],
            'sie-tik' => ['Penyelenggaraan dan pemeliharaan sistem komunikasi dan teknologi informasi kepolisian.', 'Dukungan jaringan dan sistem informasi;Pemeliharaan sarana komunikasi'],
            'sie-dokkes' => ['Pelayanan kedokteran kepolisian dan kesehatan bagi personel, tahanan, serta dukungan kesehatan operasi kepolisian.', 'Pemeriksaan kesehatan personel;Dukungan kesehatan pengamanan kegiatan'],
            'sie-keu' => ['Pelayanan administrasi keuangan, termasuk pembayaran dan pertanggungjawaban anggaran.', 'Administrasi keuangan;Pertanggungjawaban anggaran'],
            'sium' => ['Pelayanan administrasi umum dan ketatausahaan, serta pelayanan markas.', 'Administrasi persuratan;Pelayanan markas'],
            'siwas' => ['Pengawasan internal terhadap pelaksanaan tugas dan penggunaan anggaran di lingkungan Polres.', 'Pemeriksaan dan pengawasan internal;Tindak lanjut hasil pengawasan'],
        ];

        $pangkat = ['bag' => 'Kompol', 'sat' => 'AKP', 'sie' => 'AKP', 'polsek' => 'Iptu'];
        $i = 0;

        foreach (Unit::with('officials')->ordered()->get() as $unit) {
            [$tugas, $layanan] = $profiles[$unit->slug] ?? [
                'Penyelenggaraan tugas kepolisian di tingkat kecamatan: pemeliharaan kamtibmas, penegakan hukum, serta perlindungan, pengayoman, dan pelayanan kepada masyarakat.',
                'Penerimaan laporan dan pengaduan;Surat keterangan tanda lapor kehilangan;Patroli dan pengamanan wilayah;Sambang dan penyuluhan masyarakat',
            ];
            $layananHtml = '<ul>'.collect(explode(';', $layanan))->map(fn ($l) => '<li>'.e($l).'</li>')->implode('').'</ul>';

            $data = [
                'deskripsi' => $unit->type === 'polsek'
                    ? $unit->name.' melayani masyarakat di wilayah kecamatan setempat (contoh).'
                    : strtok($tugas, ',.').'.',
                'tugas_pokok' => '<p>'.e($tugas).'</p>',
                'layanan_unit' => $layananHtml,
            ];
            if ($unit->type === 'polsek') {
                $n = (int) filter_var($unit->name, FILTER_SANITIZE_NUMBER_INT) ?: 1;
                $data += [
                    'alamat' => 'Jl. Contoh No. '.$n.', Kecamatan Contoh '.$n.', Kabupaten Timor Tengah Selatan',
                    'wilayah_pelayanan' => "Kecamatan Contoh {$n}\nDesa Contoh A, Desa Contoh B, Desa Contoh C",
                ];
                $data['alamat'] = $data['alamat'];
            }
            $unit->update($data);

            $head = $unit->officials->firstWhere('level', 'pejabat');
            $name = $names[$i++ % count($names)];
            $p = $unit->slug === 'spkt' || in_array($unit->slug, ['sium', 'siwas']) ? 'Iptu' : $pangkat[$unit->type];
            if ($head) {
                $head->update(['nama' => $name, 'pangkat' => $p, 'tugas' => '<p>Memimpin pelaksanaan tugas '.e($unit->name).' (contoh).</p>']);
            }
        }
        $this->command?->line('  ✓ Profil unit + nama pejabat fiktif ('.$i.' unit)');
    }
}
