<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Database\Seeder;
use App\Models\DetailLayananTransaksi;
use App\Models\LayananTambahanTransaksi;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $jmlPelanggan = 10;
        $jmlTransaksi = 25;
        $tanggalAwal = '2024-01-01';
        $tanggalAkhir = 'now';

        // 1. Transaksi Reguler
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $date = fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta');
            $this->createTransaksiReguler(
                fake()->numberBetween(1, $jmlPelanggan),
                fake()->randomElement(['Baru', 'Proses']),
                $date->format('His'),
                $date->format('dmY'),
                $date // Mengirim objek DateTime asli
            );
        }

        // 2. Transaksi Ekspress
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $date = fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta');
            $this->createTransaksiEkspress(
                fake()->numberBetween(1, $jmlPelanggan),
                fake()->randomElement(['Baru', 'Proses']),
                $date->format('His'),
                $date->format('dmY'),
                $date
            );
        }

        // 3. Transaksi Kilat
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $date = fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta');
            $this->createTransaksiKilat(
                fake()->numberBetween(1, $jmlPelanggan),
                fake()->randomElement(['Baru', 'Proses']),
                $date->format('His'),
                $date->format('dmY'),
                $date
            );
        }

        // 4. Transaksi Selesai
        for ($i = 1; $i <= $jmlTransaksi; $i++) {
            $date = fake()->dateTimeBetween($tanggalAwal, $tanggalAkhir, 'Asia/Jakarta');
            $this->createTransaksiReguler(
                fake()->numberBetween(1, $jmlPelanggan),
                'Selesai',
                $date->format('His'),
                $date->format('dmY'),
                $date
            );
        }
    }

    public function createTransaksiReguler($pelanggan, $status, $jamNota, $tanggalNota, $tanggal)
    {
        $nota1 = $jamNota . '-' . $tanggalNota . '-' . 1 . $pelanggan;
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal->format('Y-m-d H:i:s'), // Format ke standar database
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 0,
            'total_bayar_akhir' => 138000,
            'total_biaya_layanan_tambahan' => 0,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 150000,
            'kembalian' => 12000,
            'status' => $status,
            'layanan_prioritas_id' => 1,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 1,
            'cabang_id' => 1,
        ]);

        $this->createDetails($transaksi->id, 0, 0);
    }

    public function createTransaksiEkspress($pelanggan, $status, $jamNota, $tanggalNota, $tanggal)
    {
        $nota1 = $jamNota . '-' . $tanggalNota . '-' . 1 . $pelanggan;
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal->format('Y-m-d H:i:s'),
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 54000,
            'total_bayar_akhir' => 192000,
            'total_biaya_layanan_tambahan' => 0,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 200000,
            'kembalian' => 8000,
            'status' => $status,
            'layanan_prioritas_id' => 2,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 1,
            'cabang_id' => 1,
        ]);

        $this->createDetails($transaksi->id, 36000, 18000);
    }

    public function createTransaksiKilat($pelanggan, $status, $jamNota, $tanggalNota, $tanggal)
    {
        $nota1 = $jamNota . '-' . $tanggalNota . '-' . 1 . $pelanggan;
        $transaksi = Transaksi::create([
            'nota_layanan' => 'layanan-' . $nota1,
            'nota_pelanggan' => 'pelanggan-' . $nota1,
            'waktu' => $tanggal->format('Y-m-d H:i:s'),
            'total_biaya_layanan' => 138000,
            'total_biaya_prioritas' => 72000,
            'total_bayar_akhir' => 210000,
            'total_biaya_layanan_tambahan' => 0,
            'jenis_pembayaran' => 'Tunai',
            'bayar' => 220000,
            'kembalian' => 10000,
            'status' => $status,
            'layanan_prioritas_id' => 3,
            'pelanggan_id' => $pelanggan,
            'pegawai_id' => 1,
            'cabang_id' => 1,
        ]);

        $this->createDetails($transaksi->id, 48000, 24000);
    }

    // Helper untuk membuat detail agar kode tidak duplikat banyak
    private function createDetails($transaksiId, $prioritas1, $prioritas2)
    {
        $detail1 = DetailTransaksi::create([
            'total_cucian' => 24,
            'harga_layanan_akhir' => 3500,
            'total_biaya_layanan' => 84000,
            'total_biaya_prioritas' => $prioritas1,
            'transaksi_id' => $transaksiId,
        ]);
        DetailLayananTransaksi::create(['harga_jenis_layanan_id' => 3, 'detail_transaksi_id' => $detail1->id]);
        DetailLayananTransaksi::create(['harga_jenis_layanan_id' => 4, 'detail_transaksi_id' => $detail1->id]);

        $detail2 = DetailTransaksi::create([
            'total_cucian' => 12,
            'harga_layanan_akhir' => 4500,
            'total_biaya_layanan' => 54000,
            'total_biaya_prioritas' => $prioritas2,
            'transaksi_id' => $transaksiId,
        ]);
        DetailLayananTransaksi::create(['harga_jenis_layanan_id' => 5, 'detail_transaksi_id' => $detail2->id]);
        DetailLayananTransaksi::create(['harga_jenis_layanan_id' => 6, 'detail_transaksi_id' => $detail2->id]);
    }
}
