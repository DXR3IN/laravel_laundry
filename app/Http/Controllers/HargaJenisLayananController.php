<?php

namespace App\Http\Controllers;

use App\Enums\JenisSatuanLayanan;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use App\Models\HargaJenisLayanan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HargaJenisLayananExport;
use App\Imports\HargaJenisLayananImport;
use App\Http\Requests\Layanan\HargaJenisLayananRequest;
use App\Models\JenisCucian;
use App\Models\LayananPrioritas;

class HargaJenisLayananController extends Controller
{
    public function index()
    {
        $title = "Harga Jenis Layanan";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::where('id', $userCabang)->withTrashed()->first();
        $jenisSatuanLayanan = JenisSatuanLayanan::cases();

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $prioritas = LayananPrioritas::where('cabang_id', $userCabang)->get();
        $jenisLayanan = JenisLayanan::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenisCucian = JenisCucian::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();

        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_cucian as jp', 'harga_jenis_layanan.jenis_cucian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $userCabang)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_cucian')
            ->orderBy('jenis_cucian_id', 'asc')->orderBy('jenis_layanan_id', 'asc')->get();

        $hargaJenisLayananTrash = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_cucian as jp', 'harga_jenis_layanan.jenis_cucian_id', '=', 'jp.id')
            ->where('harga_jenis_layanan.cabang_id', $userCabang)
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_cucian')
            ->onlyTrashed()->orderBy('harga_jenis_layanan.jenis_cucian_id', 'asc')->orderBy('harga_jenis_layanan.jenis_layanan_id', 'asc')->get();

        return view('dashboard.harga-jenis-layanan.index', compact('title', 'hargaJenisLayanan', 'hargaJenisLayananTrash', 'jenisLayanan', 'jenisCucian', 'prioritas', 'jenisSatuanLayanan'));
    }

    public function store(HargaJenisLayananRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($user->hasRole('manajer_laundry')) {
            $validated['cabang_id'] = $user->cabang_id;
        } else if ($user->hasRole('owner')) {
            $cabang = Cabang::where('slug', $request->cabang_slug)->firstOrFail();
            $validated['cabang_id'] = $cabang->id;
        }

        $isDuplicated = HargaJenisLayanan::where('cabang_id', $validated['cabang_id'])
            ->where('jenis_layanan_id', $validated['jenis_layanan_id'])
            ->where('jenis_cucian_id', $validated['jenis_cucian_id'])
            ->exists();

        $redirect = match (true) {
            $user->hasRole('manajer_laundry') => to_route('harga-jenis-layanan'),
            $user->hasRole('owner') => back(),
            default => abort(403, 'Aksi tidak diizinkan.'),
        };

        if ($isDuplicated) {
            return $redirect->with('error', 'Harga Jenis Layanan Sudah Ada');
        }

        if (HargaJenisLayanan::create($validated)) {
            return $redirect->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
        }

        return $redirect->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
    }

    public function show(Request $request)
    {
        $hargaJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->join('jenis_cucian as jp', 'harga_jenis_layanan.jenis_cucian_id', '=', 'jp.id')
            ->join('layanan_prioritas as lp', 'harga_jenis_layanan.prioritas_id', '=', 'lp.id')
            ->select('harga_jenis_layanan.*', 'jl.nama as nama_layanan', 'jp.nama as nama_cucian', 'lp.nama as nama_prioritas')
            ->withTrashed()->where('harga_jenis_layanan.id', $request->id)->first();

        return $hargaJenisLayanan;
    }

    public function edit(Request $request)
    {
        $hargaJenisLayanan = HargaJenisLayanan::findOrFail($request->id);
        return $hargaJenisLayanan;
    }

    public function update(HargaJenisLayananRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($user->hasRole('manajer_laundry')) {
            $validated['cabang_id'] = $user->cabang_id;
        } elseif ($user->hasRole('owner')) {
            $cabang = Cabang::where('slug', $request->cabang_slug)->firstOrFail();
            $validated['cabang_id'] = $cabang->id;
        }

        $hargaJenisLayanan = HargaJenisLayanan::findOrFail($request->id);

        $isDuplicate = HargaJenisLayanan::where('cabang_id', $validated['cabang_id'])
            ->where('jenis_layanan_id', $validated['jenis_layanan_id'])
            ->where('jenis_cucian_id', $validated['jenis_cucian_id'])
            ->where('prioritas_id', $validated['prioritas__id'])
            ->where('id', '!=', $hargaJenisLayanan->id)
            ->exists();

        $redirect = match (true) {
            $user->hasRole('manajer_laundry') => to_route('harga-jenis-layanan'),
            $user->hasRole('owner') => back(),
            default => abort(403, 'Aksi tidak diizinkan.'),
        };

        if ($isDuplicate) {
            return $redirect->with('error', 'Harga Jenis Layanan Sudah Ada');
        }

        if ($hargaJenisLayanan->update($validated)) {
            return $redirect->with('success', 'Harga Jenis Layanan Berhasil Diperbarui');
        }

        return $redirect->with('error', 'Harga Jenis Layanan Gagal Diperbarui');
    }

    public function delete(Request $request)
    {
        $hapus = HargaJenisLayanan::where('id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = HargaJenisLayanan::where('id', $request->id)->restore();
        if ($pulih) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = HargaJenisLayanan::where('id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Harga Jenis Layanan Berhasil Dihapus');
        } else {
            abort(400, 'Harga Jenis Layanan Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        try {
            Excel::import(new HargaJenisLayananImport, $request->file('impor'));
            if ($userRole == 'owner') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
            } elseif ($userRole == 'manajer_laundry') {
                return to_route('harga-jenis-layanan')->with('success', 'Harga Jenis Layanan Berhasil Ditambahkan');
            }
        } catch (\Exception $ex) {
            Log::info($ex);
            if ($userRole == 'owner') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
            } elseif ($userRole == 'manajer_laundry') {
                return to_route('harga-jenis-layanan')->with('error', 'Harga Jenis Layanan Gagal Ditambahkan');
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new HargaJenisLayananExport($request->cabang), 'Data Harga Jenis Layanan ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
}
