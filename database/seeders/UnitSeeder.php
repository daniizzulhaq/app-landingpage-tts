<?php

namespace Database\Seeders;

use App\Models\Official;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    public const PLACEHOLDER = '(diisi admin)';

    public function run(): void
    {
        $units = [
            'bag' => ['Bag Ops', 'Bag Ren', 'Bag SDM', 'Bag Log'],
            'sat' => ['Sat Intelkam', 'Sat Reskrim', 'Sat Resnarkoba', 'Sat Lantas', 'Sat Samapta', 'Sat Binmas', 'Sat Tahti', 'SPKT'],
            'sie' => ['Sie Propam', 'Sie Humas', 'Sie Hukum', 'Sie TIK', 'Sie Dokkes', 'Sie Keu', 'Sium', 'Siwas'],
            'polsek' => ['Polsek Contoh 1', 'Polsek Contoh 2', 'Polsek Contoh 3'],
        ];

        foreach ($units as $type => $names) {
            foreach ($names as $i => $name) {
                $unit = Unit::updateOrCreate(['slug' => Str::slug($name)], [
                    'type' => $type,
                    'name' => $name,
                    'deskripsi' => $name.' Polres Timor Tengah Selatan. Deskripsi lengkap '.self::PLACEHOLDER.'.',
                    'tugas_pokok' => '<p>Tugas pokok '.$name.' '.self::PLACEHOLDER.'.</p>',
                    'layanan_unit' => '<p>Layanan '.$name.' '.self::PLACEHOLDER.'.</p>',
                    'alamat' => $type === 'polsek' ? 'Alamat '.$name.' '.self::PLACEHOLDER : null,
                    'telepon' => null,
                    'email' => null,
                    'wilayah_pelayanan' => $type === 'polsek' ? 'Wilayah pelayanan '.self::PLACEHOLDER : null,
                    'urutan' => $i + 1,
                ]);

                Official::updateOrCreate(['unit_id' => $unit->id, 'level' => 'pejabat'], [
                    'nama' => self::PLACEHOLDER,
                    'pangkat' => null,
                    'jabatan' => $this->jabatan($type, $name),
                    'urutan' => $i + 1,
                ]);
            }
        }

        Official::updateOrCreate(['level' => 'kapolres'], [
            'nama' => self::PLACEHOLDER, 'jabatan' => 'Kapolres Timor Tengah Selatan', 'urutan' => 1,
            'tugas' => '<p>Profil Kapolres '.self::PLACEHOLDER.'.</p>',
        ]);
        Official::updateOrCreate(['level' => 'wakapolres'], [
            'nama' => self::PLACEHOLDER, 'jabatan' => 'Wakapolres Timor Tengah Selatan', 'urutan' => 2,
            'tugas' => '<p>Profil Wakapolres '.self::PLACEHOLDER.'.</p>',
        ]);
    }

    private function jabatan(string $type, string $name): string
    {
        return match ($type) {
            'bag' => 'Kabag '.Str::after($name, 'Bag '),
            'sat' => $name === 'SPKT' ? 'Ka SPKT' : 'Kasat '.Str::after($name, 'Sat '),
            'sie' => in_array($name, ['Sium', 'Siwas']) ? 'Ka'.Str::lower($name) : 'Kasi '.Str::after($name, 'Sie '),
            'polsek' => 'Kapolsek '.Str::after($name, 'Polsek '),
        };
    }
}
