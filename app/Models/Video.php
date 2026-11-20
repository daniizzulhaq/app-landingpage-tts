<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['judul', 'youtube_url', 'deskripsi', 'tanggal'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function youtubeId(): ?string
    {
        return youtube_id($this->youtube_url);
    }

    public function embedUrl(): ?string
    {
        $id = $this->youtubeId();

        return $id ? 'https://www.youtube-nocookie.com/embed/'.$id : null;
    }

    public function thumbnailUrl(): string
    {
        $id = $this->youtubeId();

        return $id ? 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg' : asset('img/placeholder.svg');
    }
}
