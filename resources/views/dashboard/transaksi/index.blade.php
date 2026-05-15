@extends("dashboard.layouts.main")

@section("js")
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });

            $('#myTable1').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });
        });

        @if (session()->has("success"))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has("error"))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: '{{ $title }} Gagal Dibuat',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            })
        @endif

        function delete_button(transaksi_id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dipulihkan kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('transaksi.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "transaksi_id": transaksi_id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus!',
                            })
                        }
                    });
                }
            })
        }

        function edit_status_button(transaksi_id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('transaksi.edit.status') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "transaksi_id": transaksi_id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("select[name='status'] option[value='"+items[1]+"']").prop("selected", true);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading)
                }
            });
        }
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Tabel Transaksi --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
    
                <!-- Card Header & Top Actions -->
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900">
                    <h6 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h6>
                    
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        @if (!$cabang->deleted_at)
                            <a href="{{ route("transaksi.create", ['isJadwal' => $isJadwal]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="ri-add-line text-lg leading-none"></i> Tambah Transaksi
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table id="myTable" class="w-full text-left border-collapse" style="width: 100%;">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Waktu Masuk</th>
                                <th class="px-6 py-4 text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Estimasi Selesai</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Layanan Prioritas</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Bayar</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pegawai</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                            @foreach ($transaksi as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ \Carbon\Carbon::parse($item->waktu)->format('d M Y, H:i') }}
                                    </td>
                                    
                                    {{-- KOLOM BARU: ESTIMASI SELESAI --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">
                                        @if ($item->estimasi_selesai)
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                                {{ \Carbon\Carbon::parse($item->estimasi_selesai)->format('d M Y, H:i') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">Belum diatur</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                        <span class="badge badge-primary">
                                            {{ $item->daftar_prioritas }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-white">
                                        Rp{{ number_format($item->total_bayar_akhir, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->pelanggan->nama }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        @if ($item->pegawai->roles[0]->name == 'manajer_laundry')
                                            {{ $item->pegawai->manajer[0]->nama }}
                                        @elseif ($item->pegawai->roles[0]->name == 'pegawai_laundry')
                                            {{ $item->pegawai->pegawai[0]->nama }}
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-kolom-status-transaksi :value="$item->status" />
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            <a href="{{ route("transaksi.view", ['transaksi' => $item->id, 'isJadwal' => $isJadwal]) }}" class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors tooltip" data-tip="Lihat Detail">
                                                <i class="ri-eye-line text-lg leading-none"></i>
                                            </a>
                                            
                                            @if (!$cabang->deleted_at)
                                                @if ($item->status == 'Selesai' && auth()->user()->roles[0]->name == 'pegawai_laundry')
                                                    {{-- Sembunyikan aksi jika sudah selesai dan user adalah pegawai --}}
                                                @else
                                                    <a href="{{ route("transaksi.edit", ['transaksi' => $item->id, 'isJadwal' => $isJadwal]) }}" class="inline-flex items-center justify-center p-2 text-amber-600 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-500/10 dark:hover:bg-amber-500/20 rounded-lg transition-colors tooltip" data-tip="Ubah Data">
                                                        <i class="ri-pencil-fill text-lg leading-none"></i>
                                                    </a>
                                                    
                                                    <label for="delete_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-red-600 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-500/10 dark:hover:bg-red-500/20 rounded-lg transition-colors tooltip" data-tip="Hapus Data" onclick="return delete_button('{{ $item->id }}')">
                                                        <i class="ri-delete-bin-line text-lg leading-none"></i>
                                                    </label>
                                                    
                                                    <label for="edit_status_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 rounded-lg transition-colors tooltip" data-tip="Ubah Status" onclick="return edit_status_button('{{ $item->id }}')">
                                                        <i class="ri-draft-line text-lg leading-none"></i>
                                                    </label>
                                                @endif
                                            @endif
                                            
                                            <a href="{{ route("transaksi.cetak-struk", ['transaksi' => $item->id]) }}" target="_blank" class="inline-flex items-center justify-center p-2 text-slate-600 bg-slate-100 hover:bg-slate-200 dark:text-slate-300 dark:bg-slate-700/50 dark:hover:bg-slate-700 rounded-lg transition-colors tooltip" data-tip="Cetak Struk">
                                                <i class="ri-receipt-line text-lg leading-none"></i>
                                            </a>
                                            
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Akhir Tabel Transaksi --}}

            {{-- Awal Modal Edit --}}
            <input type="checkbox" id="edit_status_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Ubah Status Transaksi</h3>
                        <label for="edit_status_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <form action="{{ route('transaksi.update.status', ['isJadwal' => $isJadwal]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" hidden>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Status</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <select name="status" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                    @foreach ($status as $item)
                                        <option value="{{ $item->value }}">{{ $item->value }}</option>
                                    @endforeach
                                </select>
                                @error("status")
                                    <div class="label">
                                        <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </label>
                            <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui Status</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Edit --}}
        </div>
    </div>
@endsection
