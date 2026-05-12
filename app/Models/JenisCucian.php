<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisCucian extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "jenis_cucian";
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'deskripsi',
        'cabang_id',
    ];

    public function hargaJenisLayanan()
    {
        return $this->hasMany(HargaJenisLayanan::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
