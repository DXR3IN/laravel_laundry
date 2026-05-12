<?php

namespace App\Http\Controllers;

use App\Exports\JenisCucianExport;
use Carbon\Carbon;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\HargaJenisLayanan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Layanan\JenisCucianRequest;
use App\Imports\JenisCucianImport;
use App\Models\JenisCucian;

class JenisCucianController extends Controller
{
    public function index()
    {
        $title = "Jenis Cucian";
        $userCabang = auth()->user()->cabang_id;
        $userRole = auth()->user()->roles[0]->name;
        $cabang = Cabang::where('id', $userCabang)->withTrashed()->first();

        if ($userRole != 'manajer_laundry') {
            return abort(403);
        }

        $jenisCucian = JenisCucian::where('cabang_id', $userCabang)->orderBy('created_at', 'asc')->get();
        $jenisCucianTrash = JenisCucian::where('cabang_id', $userCabang)->onlyTrashed()->orderBy('created_at', 'asc')->get();

        return view('dashboard.jenis-cucian.index', compact('title', 'jenisCucian', 'jenisCucianTrash', 'cabang'));
    }

    public function store(JenisCucianRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($user->hasRole('manajer_laundry')) {
            $validated['cabang_id'] = $user->cabang_id;
        } else if ($user->hasRole('owner')) {
            $cabang = Cabang::where('slug', $request->cabang_slug)->firstOrFail();
            $validated['cabang_id'] = $cabang->id;
        }

        $redirect = match (true) {
            $user->hasRole('manajer_laundry') => to_route('jenis-cucian'),
            $user->hasRole('owner') => back(),
            default => abort(403, 'Aksi tidak diizinkan.'),
        };

        if (JenisCucian::create($validated)) {
            return $redirect->with('success', 'Jenis Cucian Berhasil Ditambahkan');
        }

        return $redirect->with('error', 'Jenis Cucian Gagal Ditambahkan');
    }

    public function show(Request $request)
    {
        $jenisCucian = JenisCucian::withTrashed()->findOrFail($request->id);
        return $jenisCucian;
    }

    public function edit(Request $request)
    {
        $jenisCucian = JenisCucian::findOrFail($request->id);
        return $jenisCucian;
    }

    public function update(JenisCucianRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $redirect = match (true) {
            $user->hasRole('manajer_laundry') => to_route('jenis-cucian'),
            $user->hasRole('owner') => back(),
            default => abort(403, 'Aksi tidak diizinkan')
        };

        if (JenisCucian::where('id', $request->id)->update($validated)) {
            return $redirect->with('success', 'Jenis Cucian Berhasil Diperbarui');
        }

        return $redirect->with('error', 'Jenis Cucian Gagal Diperbarui');
    }

    public function delete(Request $request)
    {
        $hapus = JenisCucian::where('id', $request->id)->delete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_cucian_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Jenis Cucian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Cucian Gagal Dihapus');
        }
    }

    public function restore(Request $request)
    {
        $pulih = JenisCucian::where('id', $request->id)->restore();
        $cekJenisLayanan = HargaJenisLayanan::query()
            ->join('jenis_layanan as jl', 'harga_jenis_layanan.jenis_layanan_id', '=', 'jl.id')
            ->where('harga_jenis_layanan.cabang_id', $request->cabang_id)
            ->where('harga_jenis_layanan.jenis_cucian_id', $request->id)
            ->where('jl.deleted_at', null)
            ->select('harga_jenis_layanan.*', 'jl.id as id_layanan')
            ->onlyTrashed()->get();

        foreach ($cekJenisLayanan as $item) {
            HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_layanan_id', $item->id_layanan)->where('jenis_cucian_id', $request->id)->restore();
        }

        if ($pulih) {
            abort(200, 'Jenis Cucian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Cucian Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = JenisCucian::where('id', $request->id)->forceDelete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_cucian_id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Jenis Cucian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Cucian Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        try {
            Excel::import(new JenisCucianImport, $request->file('impor'));
            if ($userRole == 'owner') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('success', 'Jenis Cucian Berhasil Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('jenis-Cucian')->with('success', 'Jenis Cucian Berhasil Ditambahkan');
            }
        } catch (\Exception $ex) {
            Log::info($ex);
            if ($userRole == 'owner') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('error', 'Jenis Cucian Gagal Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('jenis-Cucian')->with('error', 'Jenis Cucian Gagal Ditambahkan');
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new JenisCucianExport($request->cabang), 'Data Jenis Cucian ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
}
