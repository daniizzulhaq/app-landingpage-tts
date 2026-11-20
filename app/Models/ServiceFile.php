<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFile extends Model
{
    protected $fillable = ['service_id', 'nama', 'path'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
