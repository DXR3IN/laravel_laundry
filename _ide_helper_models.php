<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $slug
 * @property string $lokasi
 * @property string|null $alamat
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cabang withoutTrashed()
 */
	class Cabang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $harga_jenis_layanan_id
 * @property int $detail_transaksi_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DetailTransaksi $detailTransaksi
 * @property-read \App\Models\HargaJenisLayanan|null $hargaJenisLayanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi whereDetailTransaksiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi whereHargaJenisLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailLayananTransaksi whereUpdatedAt($value)
 */
	class DetailLayananTransaksi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $total_pakaian
 * @property float $harga_layanan_akhir
 * @property float $total_biaya_layanan
 * @property float $total_biaya_prioritas
 * @property string $transaksi_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailLayananTransaksi> $detailLayananTransaksi
 * @property-read int|null $detail_layanan_transaksi_count
 * @property-read \App\Models\Transaksi $transaksi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereHargaLayananAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereTotalBiayaLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereTotalBiayaPrioritas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereTotalPakaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereTransaksiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailTransaksi whereUpdatedAt($value)
 */
	class DetailTransaksi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property float $harga
 * @property string $jenis_satuan
 * @property int $prioritas_id
 * @property int $jenis_layanan_id
 * @property int $jenis_cucian_id
 * @property int $cabang_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read \App\Models\JenisCucian|null $jenisCucian
 * @property-read \App\Models\JenisLayanan|null $jenisLayanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereJenisCucianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereJenisLayananId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereJenisSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan wherePrioritasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HargaJenisLayanan withoutTrashed()
 */
	class HargaJenisLayanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property string $cabang_id
 * @property string $alamat
 * @property string $lokasi
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HargaJenisLayanan> $hargaJenisLayanan
 * @property-read int|null $harga_jenis_layanan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisCucian withoutTrashed()
 */
	class JenisCucian extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property int $cabang_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HargaJenisLayanan> $hargaJenisLayanan
 * @property-read int|null $harga_jenis_layanan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisLayanan withoutTrashed()
 */
	class JenisLayanan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property int $prioritas
 * @property int $cabang_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas wherePrioritas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananPrioritas withoutTrashed()
 */
	class LayananPrioritas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property float $harga
 * @property int $cabang_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read \App\Models\Transaksi|null $transaksi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahan withoutTrashed()
 */
	class LayananTambahan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $layanan_tambahan_id
 * @property string $transaksi_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LayananTambahan|null $layananTambahan
 * @property-read \App\Models\Transaksi $transaksi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi whereLayananTambahanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi whereTransaksiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LayananTambahanTransaksi whereUpdatedAt($value)
 */
	class LayananTambahanTransaksi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lurah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lurah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lurah query()
 */
	class Lurah extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $foto
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $telepon
 * @property string $alamat
 * @property string|null $mulai_kerja
 * @property string|null $selesai_kerja
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereMulaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereSelesaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ManajerLaundry whereUserId($value)
 */
	class ManajerLaundry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $foto
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $telepon
 * @property string $alamat
 * @property string|null $mulai_kerja
 * @property string|null $selesai_kerja
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $remember_token
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereMulaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereSelesaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OwnerLaundry whereUserId($value)
 */
	class OwnerLaundry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $foto
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $telepon
 * @property string $alamat
 * @property string|null $mulai_kerja
 * @property string|null $selesai_kerja
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereMulaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereSelesaiKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PegawaiLaundry whereUserId($value)
 */
	class PegawaiLaundry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $slug
 * @property string $jenis_kelamin
 * @property string $telepon
 * @property string|null $alamat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PelangganFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereUpdatedAt($value)
 */
	class Pelanggan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $nota_layanan
 * @property string $nota_pelanggan
 * @property string $waktu
 * @property float $total_biaya_layanan
 * @property float $total_biaya_prioritas
 * @property float $total_biaya_layanan_tambahan
 * @property float $total_bayar_akhir
 * @property string $jenis_pembayaran
 * @property float $bayar
 * @property float $kembalian
 * @property string $status
 * @property int $layanan_prioritas_id
 * @property int $pelanggan_id
 * @property int $pegawai_id
 * @property int $cabang_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailTransaksi> $detailTransaksi
 * @property-read int|null $detail_transaksi_count
 * @property-read \App\Models\LayananPrioritas|null $layananPrioritas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LayananTambahanTransaksi> $layananTambahanTransaksi
 * @property-read int|null $layanan_tambahan_transaksi_count
 * @property-read \App\Models\User|null $pegawai
 * @property-read \App\Models\Pelanggan $pelanggan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereJenisPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereKembalian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereLayananPrioritasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereNotaLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereNotaPelanggan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi wherePelangganId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereTotalBayarAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereTotalBiayaLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereTotalBiayaLayananTambahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereTotalBiayaPrioritas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaksi whereWaktu($value)
 */
	class Transaksi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $regional
 * @property float $upah
 * @property int $tahun
 * @property int $is_used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereRegional($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereUpah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UMR whereUpdatedAt($value)
 */
	class UMR extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $username
 * @property string $slug
 * @property string $password
 * @property int|null $cabang_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cabang|null $cabang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ManajerLaundry> $manajer
 * @property-read int|null $manajer_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OwnerLaundry> $owner
 * @property-read int|null $owner_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PegawaiLaundry> $pegawai
 * @property-read int|null $pegawai_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCabangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

