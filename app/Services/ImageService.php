<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Menyimpan upload ke disk "public".
 * Gambar di-resize (maks lebar 1600px), dikompres ke WebP dan dibuatkan thumbnail 600px
 * di subfolder "thumbs". File non-gambar (PDF) disimpan apa adanya.
 */
class ImageService
{
    public const MAX_WIDTH = 1600;

    public const THUMB_WIDTH = 600;

    public static function thumbPath(string $path): string
    {
        return dirname($path).'/thumbs/'.basename($path);
    }

    public function store(UploadedFile $file, string $dir): string
    {
        $dir = trim($dir, '/');
        $mime = $file->getMimeType();

        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return $file->storeAs($dir, $this->fileName($file->getClientOriginalExtension()), 'public');
        }

        $name = $this->fileName('webp');
        $path = $dir.'/'.$name;
        $disk = Storage::disk('public');

        $image = Image::decodePath($file->getRealPath());
        $image->scaleDown(width: self::MAX_WIDTH);
        $disk->put($path, (string) $image->encode(new WebpEncoder(quality: 80)));

        $thumb = Image::decodePath($file->getRealPath());
        $thumb->scaleDown(width: self::THUMB_WIDTH);
        $disk->put(self::thumbPath($path), (string) $thumb->encode(new WebpEncoder(quality: 75)));

        return $path;
    }

    /** Hapus file beserta thumbnail-nya. */
    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }
        Storage::disk('public')->delete([$path, self::thumbPath($path)]);
    }

    /** Ganti file lama dengan upload baru (jika ada). */
    public function replace(?UploadedFile $file, ?string $old, string $dir): ?string
    {
        if (! $file) {
            return $old;
        }
        $this->delete($old);

        return $this->store($file, $dir);
    }

    private function fileName(string $ext): string
    {
        return now()->format('Ymd').'-'.Str::lower(Str::random(16)).'.'.strtolower($ext ?: 'bin');
    }
}
