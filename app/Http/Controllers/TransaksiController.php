<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\JenisLayanan;
use App\Models\JenisCucian;
use Illuminate\Http\Request;
use App\Enums\JenisPembayaran;
use App\Enums\StatusTransaksi;
use App\Models\DetailTransaksi;
use App\Models\LayananTambahan;
use Illuminate\Validation\Rule;
use App\Models\LayananPrioritas;
use App\Models\HargaJenisLayanan;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLayananTransaksi;
use App\Models\LayananTambahanTransaksi;
use Exception;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'owner') {
            $cabang = Cabang::withTrashed()->orderBy('created_at', 'asc')->get();
            return view('dashboard.transaksi.owner.index', compact('title', 'cabang'));
        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $isJadwal = false;
            $status = StatusTransaksi::cases();

            if ($userRole == "manajer_laundry") {
                $transaksi = Transaksi::query()
                    ->with(['pegawai' => function ($query) {
                        $query->withTrashed();
                    }])
                    // Tambahkan relasi detail transaksi agar loading halaman tidak lemot (Mencegah N+1 Query)
                    ->with([
                        'pelanggan:id,nama',
                        'detailTransaksi.detailLayananTransaksi.hargaJenisLayanan.jenisCucian',
                        'detailTransaksi.detailLayananTransaksi.hargaJenisLayanan.jenisLayanan',
                        'layananTambahanTransaksi.layananTambahan'
                    ])
                    ->where('cabang_id', $cabang->id)
                    ->orderBy('waktu', 'desc')
                    ->get();

                $monitoring = Transaksi::query()
                    ->with('pelanggan')
                    ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
                    ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
                    ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
                    ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
                    ->join('jenis_cucian as jp', 'jp.id', '=', 'hjl.jenis_cucian_id')
                    ->select(
                        'transaksi.id as transaksi_id',
                        'transaksi.pelanggan_id',
                        'transaksi.total_bayar_akhir',
                        DB::raw("DATE(transaksi.waktu) as tanggal"),
                        'transaksi.total_biaya_layanan_tambahan',
                    )
                    ->where('transaksi.cabang_id', $cabang->id)
                    ->where('transaksi.status', 'Selesai')
                    ->groupBy(
                        'transaksi.id',
                        'transaksi.pelanggan_id',
                        'transaksi.total_bayar_akhir',
                        DB::raw("DATE(transaksi.waktu)"),
                        'transaksi.total_biaya_layanan_tambahan',
                    )
                    ->orderBy('transaksi.waktu', 'asc')
                    ->get();
            } elseif ($userRole == "pegawai_laundry") {
                $transaksi = Transaksi::query()
                    ->with(['pegawai' => function ($query) {
                        $query->withTrashed();
                    }])
                    ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama'])
                    ->where('cabang_id', $cabang->id)
                    ->where('pegawai_id', auth()->user()->id)
                    ->orderBy('waktu', 'desc')->get();

                $monitoring = [];
            }
            return view('dashboard.transaksi.index', compact('title', 'cabang', 'transaksi', 'monitoring', 'isJadwal', 'status'));
        }
    }

    public function indexJadwal()
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = true;
        $status = StatusTransaksi::cases();

        if ($userRole == 'owner') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $userCabang = auth()->user()->cabang_id;
        $cabang = Cabang::withTrashed()->where('id', $userCabang)->first();
        if ($cabang?->deleted_at) {
            return to_route('transaksi');
        }
        $isJadwal = false;
        $status = StatusTransaksi::cases();

        if ($userRole == "manajer_laundry") {
            $transaksi = Transaksi::query()
                ->with(['pegawai' => function ($query) {
                    $query->withTrashed();
                }])
                ->with([
                    'pelanggan:id,nama',
                    'detailTransaksi.detailLayananTransaksi.hargaJenisLayanan.jenisCucian',
                    'detailTransaksi.detailLayananTransaksi.hargaJenisLayanan.jenisLayanan',
                    'layananTambahanTransaksi.layananTambahan'
                ])
                ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
                ->where('transaksi.cabang_id', $cabang->id)
                ->where('transaksi.status', '!=', 'Selesai')
                ->where('transaksi.status', '!=', 'Batal')
                ->orderBy('lp.prioritas', 'desc')
                ->orderBy('transaksi.waktu', 'asc')
                ->select('transaksi.*')
                ->get();
        } elseif ($userRole == "pegawai_laundry") {
            $transaksi = Transaksi::query()
                ->with(['pegawai' => function ($query) {
                    $query->withTrashed();
                }])
                ->with([
                    'pelanggan:id,nama',
                    'detailTransaksi.detailLayananTransaksi.hargaJenisLayanan.jenisCucian',
                    'detailTransaksi.detailLayananTransaksi.hargaJenisLayanan.jenisLayanan',
                    'layananTambahanTransaksi.layananTambahan'
                ])
                ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
                ->where('transaksi.cabang_id', $cabang->id)
                ->where('transaksi.status', '!=', 'Selesai')
                ->where('transaksi.status', '!=', 'Batal')
                ->where('pegawai_id', auth()->user()->id)
                ->orderBy('lp.prioritas', 'desc')
                ->orderBy('transaksi.waktu', 'asc')
                ->select('transaksi.*')
                ->get();
        }

        return view('dashboard.transaksi.jadwal', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
    }

    public function indexCabang(Request $request)
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = false;
        $status = StatusTransaksi::cases();

        if ($userRole != 'owner') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
        if ($cabang == null) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function ($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama'])
            ->where('cabang_id', $cabang->id)
            ->orderBy('waktu', 'desc')->get();

        $monitoring = Transaksi::query()
            ->with('pelanggan')
            ->join('detail_transaksi as dt', 'transaksi.id', '=', 'dt.transaksi_id')
            ->join('detail_layanan_transaksi as dlt', 'dt.id', '=', 'dlt.detail_transaksi_id')
            ->join('harga_jenis_layanan as hjl', 'hjl.id', '=', 'dlt.harga_jenis_layanan_id')
            ->join('jenis_layanan as jl', 'jl.id', '=', 'hjl.jenis_layanan_id')
            ->join('jenis_cucian as jp', 'jp.id', '=', 'hjl.jenis_cucian_id')
            ->select(
                'transaksi.id as transaksi_id',
                'transaksi.pelanggan_id',
                'transaksi.total_bayar_akhir',
                DB::raw("DATE(transaksi.waktu) as tanggal"),
                'transaksi.total_biaya_layanan_tambahan',
            )
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('transaksi.status', 'Selesai')
            ->groupBy(
                'transaksi.id',
                'transaksi.pelanggan_id',
                'transaksi.total_bayar_akhir',
                DB::raw("DATE(transaksi.waktu)"),
                'transaksi.total_biaya_layanan_tambahan',
            )
            ->orderBy('transaksi.waktu', 'asc')
            ->get();

        return view('dashboard.transaksi.owner.cabang', compact('title', 'cabang', 'transaksi', 'monitoring', 'isJadwal', 'status'));
    }

    public function indexCabangJadwal(Request $request)
    {
        $title = "Jadwal Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = true;
        $status = StatusTransaksi::cases();

        if ($userRole != 'owner') {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        $cabang = Cabang::where('slug', $request->cabang)->first();
        if ($cabang == null || $cabang->deleted_at) {
            abort(404, 'CABANG TIDAK DITEMUKAN ATAU SUDAH DIHAPUS.');
        }

        $transaksi = Transaksi::query()
            ->with(['pegawai' => function ($query) {
                $query->withTrashed();
            }])
            ->with(['pelanggan:id,nama', 'layananPrioritas:id,nama'])
            ->join('layanan_prioritas as lp', 'lp.id', '=', 'transaksi.layanan_prioritas_id')
            ->where('transaksi.cabang_id', $cabang->id)
            ->where('transaksi.status', '!=', 'Selesai')
            ->where('transaksi.status', '!=', 'Batal')
            ->orderBy('lp.prioritas', 'desc')
            ->orderBy('transaksi.waktu', 'asc')
            ->select('transaksi.*')
            ->get();

        return view('dashboard.transaksi.owner.jadwal', compact('title', 'cabang', 'transaksi', 'isJadwal', 'status'));
    }

    public function viewDetailTransaksi(Request $request)
    {
        $title = "Transaksi Layanan";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;

        if ($userRole == 'owner') {
            $cabang = Cabang::withTrashed()->where('slug', $request->cabang)->first();
            $transaksi = Transaksi::query()
                ->with(['pegawai' => function ($query) {
                    $query->withTrashed();
                }])
                ->where('id', $request->transaksi)->where('cabang_id', $cabang->id)->orderBy('waktu', 'asc')->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();

            $userRole = [User::withTrashed()->where('id', $transaksi->pegawai_id)->first()];

            return view('dashboard.transaksi.owner.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'isJadwal', 'layananTambahanTransaksi'));
        } else {
            $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
            $transaksi = Transaksi::query()
                ->with(['pegawai' => function ($query) {
                    $query->withTrashed();
                }])
                ->where('id', $request->transaksi)->first();

            $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
            return view('dashboard.transaksi.lihat', compact('title', 'cabang', 'transaksi', 'detailTransaksi', 'isJadwal', 'layananTambahanTransaksi'));
        }
    }

    public function createTransaksiCabang(Request $request)
    {
        $title = "Tambah Transaksi";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;
        $jenisPembayaran = JenisPembayaran::cases();

        $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
        if ($cabang->deleted_at) {
            abort(404, 'FITUR TIDAK DAPAT DIGUNAKAN.');
        }
        $pelanggan = Pelanggan::get();
        $cucian = JenisCucian::where('cabang_id', $cabang->id)->get();
        $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
        $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->get();
        return view('dashboard.transaksi.tambah', compact('title', 'cabang', 'pelanggan', 'cucian', 'layananPrioritas', 'isJadwal', 'jenisPembayaran', 'layananTambahan'));
    }

    public function storeTransaksiCabang(Request $request)
    {
        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();

        // 1. Gunakan 'numeric' agar lebih ramah terhadap data angka dari JavaScript
        $validatorTransaksi = Validator::make(
            $request->all(),
            [
                'total_biaya_layanan' => 'required|numeric',
                'total_biaya_layanan_tambahan' => 'required|numeric',
                'total_bayar_akhir' => 'required|numeric',
                'jenis_pembayaran' => 'required|string|max:255',
                'bayar' => 'required|numeric',
                'kembalian' => 'required|numeric',
                'pelanggan_id' => 'required|integer',
            ]
        );

        // 2. TANGKAP ERROR VALIDASI agar bisa dibaca oleh AJAX
        if ($validatorTransaksi->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validatorTransaksi->errors()], 422);
        }

        try {
            $validatedTransaksi = $validatorTransaksi->validated();

            // --- PENGAMAN DATABASE ---
            // Karena database kamu kemungkinan masih mewajibkan 2 kolom ini,
            // kita isi paksa nilainya agar MySQL tidak marah (bisa dihapus nanti jika tabel sudah di-migrate ulang)
            $validatedTransaksi['total_biaya_prioritas'] = 0;

            // Cari ID Prioritas pertama yang ada di cabang ini untuk sekadar mengisi kolom yang wajib
            $prioritasBawaan = LayananPrioritas::where('cabang_id', $cabang->id)->first();
            if ($prioritasBawaan) {
                $validatedTransaksi['layanan_prioritas_id'] = $prioritasBawaan->id;
            }
            // -------------------------

            $validatedTransaksi['cabang_id'] = $cabang->id;
            $validatedTransaksi['pegawai_id'] = auth()->user()->id;
            $validatedTransaksi['waktu'] = Carbon::now();
            $nota = Carbon::now()->format('His') . "-" . Carbon::now()->format('dmY') . "-" . $cabang->id . $request->pelanggan_id;
            $validatedTransaksi['nota_layanan'] = "layanan-" . $nota;
            $validatedTransaksi['nota_pelanggan'] = "pelanggan-" . $nota;
            $validatedTransaksi['status'] = StatusTransaksi::BARU->value;

            $maxWaktu = 0;
            if ($request->harga_jenis_layanan_id) {
                foreach ($request->harga_jenis_layanan_id as $hjl_id) {
                    $hjl = HargaJenisLayanan::with('layananPrioritas')->find($hjl_id);

                    // Asumsi: 'prioritas' (atau 'nilai_prioritas') adalah angka total hari/jam
                    $nilaiPrioritas = $hjl->layananPrioritas->prioritas;

                    if ($nilaiPrioritas > $maxWaktu) {
                        $maxWaktu = $nilaiPrioritas;
                    }
                }
            }

            // 2. Masukkan ke data tervalidasi
            $validatedTransaksi['waktu'] = Carbon::now();

            // Asumsi: Jika nilai prioritasmu adalah hitungan HARI. 
            // Jika hitungannya JAM, ganti addDays() menjadi addHours()
            $validatedTransaksi['estimasi_selesai'] = Carbon::now()->addDays($maxWaktu);

            // Simpan Transaksi Induk
            $transaksi = Transaksi::create($validatedTransaksi);

            // 3. Simpan Detail Transaksi
            if ($request->harga_jenis_layanan_id) {
                foreach ($request->harga_jenis_layanan_id as $index => $hjl_id) {
                    // Ambil data harga paket beserta relasi prioritasnya
                    $hjl = HargaJenisLayanan::with('layananPrioritas')->find($hjl_id);
                    $qty = $request->total_cucian[$index];
                    $subtotal = $hjl->harga * $qty;

                    // Hitung estimasi selesai khusus untuk cucian INI saja
                    $nilaiPrioritas = $hjl->layananPrioritas->prioritas ?? 0;
                    $estimasiCucian = \Carbon\Carbon::now()->addDays($nilaiPrioritas);

                    $detailTransaksi = DetailTransaksi::create([
                        'total_cucian' => $qty,
                        'harga_layanan_akhir' => $hjl->harga,
                        'total_biaya_layanan' => $subtotal,
                        'total_biaya_prioritas' => 0,
                        'transaksi_id' => $transaksi->id,

                        // MASUKKAN DATA BARU KE SINI
                        'estimasi_selesai' => $estimasiCucian,
                        'status' => 'Baru', // Status default
                    ]);

                    DetailLayananTransaksi::create([
                        'harga_jenis_layanan_id' => $hjl->id,
                        'detail_transaksi_id' => $detailTransaksi->id,
                    ]);
                }
            }

            // 4. Simpan Layanan Tambahan
            if ($request->layanan_tambahan_id) {
                foreach ($request->layanan_tambahan_id as $item) {
                    LayananTambahanTransaksi::create([
                        'layanan_tambahan_id' => $item,
                        'transaksi_id' => $transaksi->id,
                    ]);
                }
            }

            return response()->json($transaksi);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan sistem',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function editTransaksiCabang(Request $request)
    {
        $title = "Ubah Transaksi";
        $userRole = auth()->user()->roles[0]->name;
        $isJadwal = $request->isJadwal;
        $status = StatusTransaksi::cases();
        $jenisPembayaran = JenisPembayaran::cases();
        $cabang = Cabang::withTrashed()->where('id', auth()->user()->cabang_id)->first();
        if ($cabang->deleted_at) {
            abort(404, 'FITUR TIDAK DAPAT DIGUNAKAN.');
        }
        $pelanggan = Pelanggan::get();
        $cucian = JenisCucian::where('cabang_id', $cabang->id)->get();
        $layananPrioritas = LayananPrioritas::where('cabang_id', $cabang->id)->get();
        $layananTambahan = LayananTambahan::where('cabang_id', $cabang->id)->get();
        $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

        if ($transaksi->status == 'Selesai' && $userRole == 'pegawai_laundry') {
            abort(403, 'Transaksi Ini Tidak Dapat Diubah');
        }

        $hargaLayanan = HargaJenisLayanan::where('cabang_id', $cabang->id)->get();
        $layanan = JenisLayanan::where('cabang_id', $cabang->id)->get();

        return view('dashboard.transaksi.ubah', compact('title', 'cabang', 'status', 'pelanggan', 'cucian', 'layananPrioritas', 'transaksi', 'layanan', 'hargaLayanan', 'isJadwal', 'jenisPembayaran', 'layananTambahan'));
    }

    public function updateTransaksiCabang(Request $request)
    {
        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
        $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi)->first();

        // 1. Ganti 'decimal' menjadi 'numeric'
        $validatorTransaksi = Validator::make(
            $request->all(),
            [
                'total_biaya_layanan' => 'required|numeric',
                'total_biaya_layanan_tambahan' => 'required|numeric',
                'total_bayar_akhir' => 'required|numeric',
                'jenis_pembayaran' => 'required|string|max:255',
                'bayar' => 'required|numeric',
                'kembalian' => 'required|numeric',
                'status' => ['required', Rule::in(StatusTransaksi::cases())],
                'pelanggan_id' => 'required|integer',
            ]
        );

        if ($validatorTransaksi->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validatorTransaksi->errors()], 422);
        }

        try {
            $validatedTransaksi = $validatorTransaksi->validated();

            // --- PENGAMAN DATABASE (Sama seperti Store) ---
            $validatedTransaksi['total_biaya_prioritas'] = 0;
            $prioritasBawaan = LayananPrioritas::where('cabang_id', $cabang->id)->first();
            if ($prioritasBawaan) {
                $validatedTransaksi['layanan_prioritas_id'] = $prioritasBawaan->id;
            }

            $maxWaktu = 0;
            if ($request->harga_jenis_layanan_id) {
                foreach ($request->harga_jenis_layanan_id as $hjl_id) {
                    $hjl = HargaJenisLayanan::with('layananPrioritas')->find($hjl_id);

                    // Asumsi: 'prioritas' (atau 'nilai_prioritas') adalah angka total hari/jam
                    $nilaiPrioritas = $hjl->layananPrioritas->prioritas;

                    if ($nilaiPrioritas > $maxWaktu) {
                        $maxWaktu = $nilaiPrioritas;
                    }
                }
            }

            // 2. Masukkan ke data tervalidasi
            $validatedTransaksi['waktu'] = Carbon::now();

            // Asumsi: Jika nilai prioritasmu adalah hitungan HARI. 
            // Jika hitungannya JAM, ganti addDays() menjadi addHours()
            $validatedTransaksi['estimasi_selesai'] = Carbon::now()->addDays($maxWaktu);

            // 2. Update Transaksi Induk
            Transaksi::where('cabang_id', $cabang->id)->where('id', $getTransaksi->id)->update($validatedTransaksi);

            // 3. Bersihkan data Detail lama (Hapus yang lama sebelum insert yang baru)
            $detailTransaksiLama = DetailTransaksi::where('transaksi_id', $getTransaksi->id)->get();
            foreach ($detailTransaksiLama as $item) {
                DetailLayananTransaksi::where('detail_transaksi_id', $item->id)->delete();
            }
            DetailTransaksi::where('transaksi_id', $getTransaksi->id)->delete();

            // 4. Masukkan data Detail baru (Logika baru tanpa nested loop)
            // 4. Masukkan data Detail baru
            if ($request->harga_jenis_layanan_id) {
                foreach ($request->harga_jenis_layanan_id as $index => $hjl_id) {
                    $hjl = HargaJenisLayanan::find($hjl_id);
                    $qty = $request->total_cucian[$index];
                    $subtotal = $hjl->harga * $qty;

                    // Ambil nilai prioritas terlama lagi khusus baris ini untuk estimasi (seperti di fungsi store)
                    $hjlLengkap = HargaJenisLayanan::with('layananPrioritas')->find($hjl_id);
                    $nilaiPrioritas = $hjlLengkap->layananPrioritas->prioritas ?? 0;

                    $newDetail = DetailTransaksi::create([
                        'total_cucian' => $qty,
                        'harga_layanan_akhir' => $hjl->harga,
                        'total_biaya_layanan' => $subtotal,
                        'total_biaya_prioritas' => 0,
                        'transaksi_id' => $getTransaksi->id,

                        // MASUKKAN ESTIMASI & STATUS PER ITEM DI SINI
                        'estimasi_selesai' => \Carbon\Carbon::now()->addDays($nilaiPrioritas),
                        'status' => $request->status_cucian[$index] ?? 'Baru',
                    ]);

                    DetailLayananTransaksi::create([
                        'harga_jenis_layanan_id' => $hjl->id,
                        'detail_transaksi_id' => $newDetail->id,
                    ]);
                }
            }

            // 5. Update Layanan Tambahan
            LayananTambahanTransaksi::where('transaksi_id', $getTransaksi->id)->delete();
            if ($request->layanan_tambahan_id) {
                foreach ($request->layanan_tambahan_id as $item) {
                    LayananTambahanTransaksi::create([
                        'layanan_tambahan_id' => $item,
                        'transaksi_id' => $getTransaksi->id,
                    ]);
                }
            }

            return response()->json(['message' => 'Transaksi berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan sistem',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function editStatusTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
        $transaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi_id)->first(['id', 'status']);
        return $transaksi;
    }

    public function updateStatusTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;


        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
        $perbarui = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->id)->update(['status' => $request->status]);

        if ($request->isJadwal) {
            if ($perbarui) {
                return to_route('transaksi.jadwal')->with('success', 'Status Transaksi Berhasil Diperbarui');
            } else {
                return to_route('transaksi.jadwal')->with('error', 'Status Transaksi Gagal Diperbarui');
            }
        } else {
            if ($perbarui) {
                return to_route('transaksi')->with('success', 'Status Transaksi Berhasil Diperbarui');
            } else {
                return to_route('transaksi')->with('error', 'Status Transaksi Gagal Diperbarui');
            }
        }
    }

    public function deleteTransaksiCabang(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
        $getTransaksi = Transaksi::where('cabang_id', $cabang->id)->where('id', $request->transaksi_id)->first();

        $detailTransaksi = DetailTransaksi::where('transaksi_id', $getTransaksi->id)->get();
        foreach ($detailTransaksi as $item) {
            DetailLayananTransaksi::where('detail_transaksi_id', $item->id)->delete();
        }
        DetailTransaksi::where('transaksi_id', $getTransaksi->id)->delete();
        $hapus = Transaksi::where('id', $request->transaksi_id)->delete();

        if ($hapus) {
            abort(200, 'Transaksi Berhasil Dihapus');
        } else {
            abort(400, 'Transaksi Gagal Dihapus');
        }
    }

    public function ubahJenisCucian(Request $request)
    {
        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();

        // Gunakan JOIN agar pasti berhasil mengambil nama layanan dan prioritas
        $paketLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            // Sesuaikan 'prioritas_id' dengan nama kolom prioritas di tabel harga_jenis_layanan kamu
            ->join('layanan_prioritas as lp', 'harga_jenis_layanan.prioritas_id', '=', 'lp.id')
            ->where('harga_jenis_layanan.cabang_id', $cabang->id)
            ->where('harga_jenis_layanan.jenis_cucian_id', $request->jenisCucianId)
            ->select('harga_jenis_layanan.id', 'harga_jenis_layanan.harga', 'jl.nama as nama_layanan', 'lp.nama as nama_prioritas')
            ->get();

        return response()->json($paketLayanan);
    }

    public function ubahJenisLayanan(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
        $hargaLayananAkhir = 0;
        foreach ($request->jenisLayananId as $item) {
            $hargaLayanan = HargaJenisLayanan::query()
                ->where('cabang_id', $cabang->id)
                ->where('jenis_cucian_id', $request->jenisCucianId)
                ->where('jenis_layanan_id', $item)
                ->first();
            $hargaLayananAkhir += $hargaLayanan->harga;
        }
        return $hargaLayananAkhir;
    }

    public function ubahLayananTambahan(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;

        $cabang = Cabang::where('id', auth()->user()->cabang_id)->first();
        $hargaLayananAkhir = 0;
        foreach ($request->layananTambahanId as $item) {
            $hargaLayanan = LayananTambahan::query()
                ->where('cabang_id', $cabang->id)
                ->where('id', $item)
                ->first();
            $hargaLayananAkhir += $hargaLayanan->harga;
        }
        return $hargaLayananAkhir;
    }

    public function hitungTotalBayar(Request $request)
    {
        $biayaLayanan = 0;

        if ($request->hargaLayanan) {
            foreach ($request->hargaLayanan as $item => $harga) {
                $biayaLayanan += $harga * $request->totalCucian[$item];
            }
        }

        $totalBayar = $biayaLayanan + $request->layananTambahan;

        return [$biayaLayanan, 0, $totalBayar];
    }

    public function cetakStrukTransaksi(Request $request)
    {
        $title = "Cetak Struk";
        $transaksi = Transaksi::query()
            ->with(['pegawai' => function ($query) {
                $query->withTrashed();
            }])
            ->where('id', $request->transaksi)->first();
        $detailTransaksi = DetailTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        $layananTambahanTransaksi = LayananTambahanTransaksi::where('transaksi_id', $transaksi->id)->orderBy('id', 'asc')->get();
        $cabang = Cabang::where('id', $transaksi->cabang_id)->first();

        // $pdf = Pdf::loadView('dashboard.transaksi.struk.index', [
        //     'judul' => $title,
        //     'transaksi' => $transaksi,
        //     'detailTransaksi' => $detailTransaksi,
        //     'footer' => $title
        // ])
        // ->setPaper('a4', 'potrait');
        // return $pdf->stream();

        return view('dashboard.transaksi.struk.index', compact('title', 'transaksi', 'detailTransaksi', 'cabang', 'layananTambahanTransaksi'));
    }

    // public function konfirmasiUpah(Request $request)
    // {
    //     if (!$request->konfirmasi) {
    //         Transaksi::where('id', $request->transaksi_id)->update([
    //             'konfirmasi_upah_gamis' => true
    //         ]);
    //     } else {
    //         Transaksi::where('id', $request->transaksi_id)->update([
    //             'konfirmasi_upah_gamis' => false
    //         ]);
    //     }
    // }
}
