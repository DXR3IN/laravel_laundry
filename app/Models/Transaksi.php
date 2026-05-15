<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nota_layanan',
        'nota_pelanggan',
        'waktu',
        'total_biaya_layanan',
        'total_biaya_prioritas',
        'total_biaya_layanan_tambahan',
        'total_bayar_akhir',
        'jenis_pembayaran',
        'bayar',
        'kembalian',
        'estimasi_selesai',
        'status',
        'layanan_prioritas_id',
        'pelanggan_id',
        'pegawai_id',
        'cabang_id',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function layananTambahanTransaksi()
    {
        return $this->hasMany(LayananTambahanTransaksi::class);
    }

    public function layananPrioritas()
    {
        return $this->belongsTo(LayananPrioritas::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    // Tambahkan ini di Model Transaksi.php
    // Merangkum isi detail transaksi dan layanan tambahan
    public function getIsiPesananAttribute()
    {
        $pesanan = [];

        // 1. Ambil dari Cucian Utama (Detail Transaksi)
        foreach ($this->detailTransaksi as $dt) {
            // Kita cek apakah relasinya ada agar tidak error
            if (isset($dt->detailLayananTransaksi[0]) && $dt->detailLayananTransaksi[0]->hargaJenisLayanan) {
                $hjl = $dt->detailLayananTransaksi[0]->hargaJenisLayanan;

                $namaCucian = $hjl->jenisCucian ? $hjl->jenisCucian->nama : '';
                $namaLayanan = $hjl->jenisLayanan ? $hjl->jenisLayanan->nama : '';
                $qty = $dt->total_cucian;

                // Format: Cuci Sepatu Hitam (2)
                $pesanan[] = "<span class='text-blue-600 dark:text-blue-400 font-medium'>{$namaLayanan} {$namaCucian}</span> <span class='text-slate-500'>({$qty})</span>";
            }
        }

        // 2. Ambil dari Layanan Tambahan (Jika ada)
        foreach ($this->layananTambahanTransaksi as $ltt) {
            if ($ltt->layananTambahan) {
                // Format: + Parfum Ekstra
                $pesanan[] = "<span class='text-emerald-600 dark:text-emerald-400 text-xs'><i class='ri-add-line'></i> {$ltt->layananTambahan->nama}</span>";
            }
        }

        // Gabungkan array menjadi string dengan pemisah baris (<br>)
        if (count($pesanan) > 0) {
            return implode('<br>', $pesanan);
        }

        return "-";
    }
}
