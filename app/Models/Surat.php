<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surat extends Model
{
    protected $table = 'surat';
    protected $guarded = ['id'];

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'bagian_id', 'id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Bagian::class, 'ditujukan', 'id');
    }

    public function status(): HasMany
    {
        return $this->hasMany(StatusSurat::class, 'surat_id', 'id');
    }

    public function statusTerakhir()
    {
        return $this->hasOne(StatusSurat::class, 'surat_id', 'id')->latestOfMany();
    }

}
