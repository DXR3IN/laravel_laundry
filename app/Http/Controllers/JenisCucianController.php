<?php

namespace App\Http\Controllers;

use App\Exports\JenisCucianExport;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\JenisPakaian;
use Illuminate\Http\Request;
use App\Models\HargaJenisLayanan;
use App\Exports\JenisPakaianExport;
use App\Imports\JenisPakaianImport;
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
        $userRole = auth()->user()->roles[0]->name;

        if ($userRole == 'manajer_laundry') {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else if ($userRole == 'pic') {
            $cabang = Cabang::where('slug', $request->cabang_slug)->first();
            $validated['cabang_id'] = $cabang->id;
        }

        $tambah = JenisCucian::create($validated);

        if ($userRole == 'manajer_laundry') {
            if ($tambah) {
                return to_route('jenis-cucian')->with('success', 'Jenis Pakaian Berhasil Ditambahkan');
            } else {
                return to_route('jenis-cucian')->with('error', 'Jenis Pakaian Gagal Ditambahkan');
            }
        } else if ($userRole == 'pic') {
            if ($tambah) {
                return back()->with('success', 'Jenis Cucian Berhasil Ditambahkan');
            } else {
                return back()->with('error', 'Jenis Cucian Gagal Ditambahkan');
            }
        }
    }

    public function show(Request $request)
    {
        $jenisPakaian = JenisCucian::withTrashed()->findOrFail($request->id);
        return $jenisPakaian;
    }

    public function edit(Request $request)
    {
        $jenisCucian = JenisCucian::findOrFail($request->id);
        return $jenisCucian;
    }

    public function update(JenisCucianRequest $request)
    {
        $validated = $request->validated();
        $userRole = auth()->user()->roles[0]->name;
        $perbarui = JenisCucian::where('id', $request->id)->update($validated);

        if ($userRole == 'manajer_laundry') {
            if ($perbarui) {
                return to_route('jenis-pakaian')->with('success', 'Jenis Pakaian Berhasil Diperbarui');
            } else {
                return to_route('jenis-pakaian')->with('error', 'Jenis Pakaian Gagal Diperbarui');
            }
        } else if ($userRole == 'pic') {
            if ($perbarui) {
                return back()->with('success', 'Jenis Pakaian Berhasil Diperbarui');
            } else {
                return back()->with('error', 'Jenis Pakaian Gagal Diperbarui');
            }
        }
    }

    public function delete(Request $request)
    {
        $hapus = JenisCucian::where('id', $request->id)->delete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_cucian_id', $request->id)->delete();
        if ($hapus) {
            abort(200, 'Jenis Pakaian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Pakaian Gagal Dihapus');
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
            abort(200, 'Jenis Pakaian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Pakaian Gagal Dihapus');
        }
    }

    public function destroy(Request $request)
    {
        $hapusPermanen = JenisCucian::where('id', $request->id)->forceDelete();
        HargaJenisLayanan::where('cabang_id', $request->cabang_id)->where('jenis_cucian_id', $request->id)->forceDelete();
        if ($hapusPermanen) {
            abort(200, 'Jenis Pakaian Berhasil Dihapus');
        } else {
            abort(400, 'Jenis Pakaian Gagal Dihapus');
        }
    }

    public function import(Request $request)
    {
        $userRole = auth()->user()->roles[0]->name;
        try {
            Excel::import(new JenisCucianImport, $request->file('impor'));
            if ($userRole == 'pic') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('success', 'Jenis Pakaian Berhasil Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('jenis-pakaian')->with('success', 'Jenis Pakaian Berhasil Ditambahkan');
            }
        } catch(\Exception $ex) {
            Log::info($ex);
            if ($userRole == 'pic') {
                return to_route('layanan-cabang.cabang', $request->cabang)->with('error', 'Jenis Pakaian Gagal Ditambahkan');
            } else if ($userRole == 'manajer_laundry') {
                return to_route('jenis-pakaian')->with('error', 'Jenis Pakaian Gagal Ditambahkan');
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new JenisCucianExport($request->cabang), 'Data Jenis Pakaian '.Carbon::now()->format('d-m-Y').'.xlsx');
    }
}
