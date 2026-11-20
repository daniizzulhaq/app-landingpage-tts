<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['pertanyaan', 'jawaban', 'urutan'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }
}
