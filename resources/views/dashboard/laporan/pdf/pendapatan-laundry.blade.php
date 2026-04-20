@extends('dashboard.laporan.layouts.main') 
{{-- CATATAN: Pastikan di dalam file layout 'main' ini TIDAK ADA link ke file Tailwind CSS. Jika ada, lebih baik buat layout baru khusus PDF --}}

@section('tanggal')
    <p style="padding-bottom: 0px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</span></p>
    <p style="padding-bottom: 20px">Cabang: <span style="font-weight: 500">{{ $nama_cabang ? $nama_cabang->nama : 'Semua Cabang' }}</span></p>
@endsection

@section('tabel')
    <style>
        /* CSS Murni khusus untuk PDF, sangat ringan dan tidak bikin Dompdf hang */
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            font-family: sans-serif;
            font-size: 12px;
        }
        .pdf-table th, .pdf-table td {
            border-bottom: 1px solid #94a3b8; /* Pengganti border-slate-600 */
            padding: 8px;
            text-align: left;
            vertical-align: middle;
            color: #475569; /* Pengganti text-slate-500 */
        }
        .pdf-table th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        .total-label {
            font-size: 11px;
            color: #666;
        }
        .total-value {
            font-weight: bold;
            font-size: 14px;
            color: #000;
        }
    </style>

    <table class="pdf-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Layanan Prioritas</th>
                <th>Pelanggan</th>
                <th>Pegawai</th>
                <th>Total Bayar</th>
                <th>Pendapatan Laundry</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->layananPrioritas->nama ?? '-' }}</td>
                    <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                    <td>
                        @if (isset($item->pegawai->roles[0]) && $item->pegawai->roles[0]->name == 'manajer_laundry')
                            {{ $item->pegawai->manajer[0]->nama ?? '-' }}
                        @elseif (isset($item->pegawai->roles[0]) && $item->pegawai->roles[0]->name == 'pegawai_laundry')
                            {{ $item->pegawai->pegawai[0]->nama ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp{{ number_format($item->total_bayar_akhir, 2, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->pendapatan_laundry, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse

            {{-- Baris Total --}}
            @if($transaksi->isNotEmpty())
            <tr class="total-row">
                <td colspan="4"></td>
                <td>
                    <div class="total-label">Pendapatan Kotor</div>
                    <div class="total-value">Rp{{ number_format($transaksi->sum('total_bayar_akhir'), 2, ',', '.') }}</div>
                </td>
                <td>
                    <div class="total-label">Pendapatan Bersih</div>
                    <div class="total-value">Rp{{ number_format($transaksi->sum('pendapatan_laundry'), 2, ',', '.') }}</div>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
@endsection