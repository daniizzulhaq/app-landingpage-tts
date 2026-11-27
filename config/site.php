<?php

/*
|--------------------------------------------------------------------------
| Skema Pengaturan Situs
|--------------------------------------------------------------------------
| Daftar field yang muncul di menu Admin > Pengaturan dan disimpan di tabel
| settings (key/value). type: text, textarea, email, url, image, boolean.
*/

return [
    'settings' => [
        'Identitas' => [
            'site_name' => ['label' => 'Nama situs', 'type' => 'text', 'rules' => 'required|string|max:100'],
            'site_tagline' => ['label' => 'Tagline', 'type' => 'text', 'rules' => 'nullable|string|max:150'],
            'site_motto' => ['label' => 'Motto', 'type' => 'text', 'rules' => 'nullable|string|max:150'],
            'meta_description' => ['label' => 'Deskripsi situs (SEO)', 'type' => 'textarea', 'rules' => 'nullable|string|max:300'],
            'logo' => ['label' => 'Logo', 'type' => 'image'],
            'og_image' => ['label' => 'Gambar bagikan default (Open Graph)', 'type' => 'image'],
        ],
        'Kontak' => [
            'alamat' => ['label' => 'Alamat kantor', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
            'telepon' => ['label' => 'Telepon kantor', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
            'whatsapp' => ['label' => 'Nomor WhatsApp layanan', 'type' => 'text', 'rules' => 'nullable|string|max:30', 'help' => 'Format 62xxxxxxxx (tanpa + atau spasi).'],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:150'],
            'jam_pelayanan' => ['label' => 'Jam pelayanan', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
            'map_embed' => ['label' => 'Embed peta Google Maps', 'type' => 'textarea', 'rules' => 'nullable|string|max:3000', 'help' => 'Tempel kode <iframe> atau URL embed dari Google Maps > Bagikan > Sematkan peta.'],
            'map_link' => ['label' => 'Tautan Google Maps (petunjuk arah)', 'type' => 'url', 'rules' => 'nullable|url|max:1000'],
        ],
        'Media Sosial' => [
            'facebook' => ['label' => 'Facebook', 'type' => 'url', 'rules' => 'nullable|url|max:300'],
            'instagram' => ['label' => 'Instagram', 'type' => 'url', 'rules' => 'nullable|url|max:300'],
            'x' => ['label' => 'X (Twitter)', 'type' => 'url', 'rules' => 'nullable|url|max:300'],
            'youtube' => ['label' => 'YouTube', 'type' => 'url', 'rules' => 'nullable|url|max:300'],
            'tiktok' => ['label' => 'TikTok', 'type' => 'url', 'rules' => 'nullable|url|max:300'],
        ],
        'Struktur & PPID' => [
            'bagan_struktur' => ['label' => 'Gambar bagan struktur organisasi', 'type' => 'image'],
            'ppid_standar_pelayanan' => ['label' => 'Standar pelayanan PPID', 'type' => 'editor', 'rules' => 'nullable|string|max:30000'],
        ],
        'Sistem' => [
            'require_approval' => ['label' => 'Konten Admin Unit wajib disetujui Super Admin sebelum tampil', 'type' => 'boolean'],
        ],
    ],

    'social_icons' => [
        'facebook' => 'bi-facebook',
        'instagram' => 'bi-instagram',
        'x' => 'bi-twitter-x',
        'youtube' => 'bi-youtube',
        'tiktok' => 'bi-tiktok',
    ],
];
