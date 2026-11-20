<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Service;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceAgendaSeeder extends Seeder
{
    private const P = '(diisi admin)';

    public function run(): void
    {
        $services = [
            ['Surat Izin Mengemudi (SIM)', 'bi-person-vcard', 'sat-lantas', 'Penerbitan dan perpanjangan Surat Izin Mengemudi.'],
            ['Surat Keterangan Catatan Kepolisian (SKCK)', 'bi-file-earmark-check', 'sat-intelkam', 'Penerbitan Surat Keterangan Catatan Kepolisian.'],
            ['Izin Keramaian', 'bi-people', 'sat-intelkam', 'Pemberitahuan dan perizinan kegiatan keramaian umum.'],
            ['Surat Keterangan Kehilangan', 'bi-search', 'spkt', 'Penerbitan surat keterangan tanda lapor kehilangan barang/dokumen.'],
        ];

        foreach ($services as $i => [$nama, $ikon, $unit, $desk]) {
            Service::firstOrCreate(['nama' => $nama], [
                'ikon' => $ikon,
                'unit_id' => Unit::where('slug', $unit)->value('id'),
                'deskripsi' => $desk,
                'persyaratan' => "Persyaratan 1 ".self::P."\nPersyaratan 2 ".self::P."\nPersyaratan 3 ".self::P,
                'prosedur' => "Langkah 1 ".self::P."\nLangkah 2 ".self::P."\nLangkah 3 ".self::P,
                'biaya' => self::P,
                'jadwal' => self::P,
                'lokasi' => self::P,
                'urutan' => $i + 1,
            ]);
        }

        if (! Agenda::exists()) {
            $admin = User::where('email', 'admin@polrestts.test')->first();
            $agendas = [
                ['Contoh Agenda: Pelayanan SIM Keliling', 'sat-lantas', 3, 'Lokasi '.self::P],
                ['Contoh Agenda: Sosialisasi Kamtibmas di Sekolah', 'sat-binmas', 7, 'Lokasi '.self::P],
                ['Contoh Agenda: Rapat Koordinasi Pengamanan', 'bag-ops', 12, 'Mapolres '.self::P],
                ['Contoh Agenda: Penyuluhan Hukum untuk Masyarakat', 'sie-hukum', -10, 'Lokasi '.self::P],
            ];
            foreach ($agendas as [$judul, $unit, $days, $lokasi]) {
                $mulai = now()->addDays($days)->setTime(9, 0);
                Agenda::create([
                    'unit_id' => Unit::where('slug', $unit)->value('id'),
                    'user_id' => $admin?->id,
                    'judul' => $judul,
                    'deskripsi' => 'Agenda contoh. Ganti dengan agenda resmi melalui panel admin.',
                    'lokasi' => $lokasi,
                    'mulai' => $mulai,
                    'selesai' => $mulai->copy()->setTime(12, 0),
                    'status' => 'published',
                    'published_at' => now(),
                    'approved_by' => $admin?->id,
                ]);
            }
        }
    }
}
