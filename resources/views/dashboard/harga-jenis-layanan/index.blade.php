@extends('dashboard.layouts.main')

@section('js')
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

        @if (session()->has('success'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('error') }}',
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

        function show_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4, #loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('harga-jenis-layanan.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);

                    $("input[name='harga']").val(data.harga);
                    $("input[name='jenis_satuan']").val(data.jenis_satuan);
                    $("input[name='jenis_layanan_id']").val(data.nama_layanan);
                    $("input[name='jenis_cucian_id']").val(data.nama_cucian);
                    $("input[name='prioritas_id']").val(data.nama_prioritas);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4, #loading_edit5").empty();
                }
            });
        }

        function edit_button(id) {
            // 1. Loading effect start (Bisa digabung agar lebih ringkas)
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4, #loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('harga-jenis-layanan.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // 2. Isi form input biasa langsung menggunakan nama kolom (key) dari object JSON
                    $("input[name='id']").val(data.id);
                    $("input[name='harga']").val(data.harga);

                    // 3. Render ulang option murni pakai Blade (tanpa logika IF selected yang dicampur JS)
                    $("#jenis_layanan_select").html(`
                        <option disabled>Pilih Jenis Layanan!</option>
                        @foreach ($jenisLayanan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    `);

                    $("#jenis_cucian_select").html(`
                        <option disabled>Pilih Jenis Cucian!</option>
                        @foreach ($jenisCucian as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    `);

                    $("#prioritas_select").html(`
                        <option disabled>Pilih Prioritas!</option>
                        @foreach ($prioritas as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    `);

                    $("#jenis_satuan_select").html(`
                        <option disabled>Pilih Jenis Satuan!</option>
                        @foreach ($jenisSatuanLayanan as $item)
                            <option value="{{ $item->value }}">{{ $item->value }}</option>
                        @endforeach
                    `);

                    // 4. JADI SANGAT MUDAH: Gunakan jQuery .val() untuk memilih option otomatis!
                    // jQuery akan mencari <option value="..."> yang cocok dan menjadikannya 'selected'
                    $("#jenis_layanan_select").val(data.jenis_layanan_id);
                    $("#jenis_cucian_select").val(data.jenis_cucian_id);
                    $("#jenis_satuan_select").val(data.jenis_satuan);
                    $("#prioritas_select").val(data.prioritas_id);

                    // 5. Loading effect end
                    $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4, #loading_edit5").empty();
                },
                error: function() {
                    alert("Terjadi kesalahan saat mengambil data dari server.");
                    $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4, #loading_edit5").empty();
                }
            });
        }

        function delete_button(id, cabang_id, layanan, cucian) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan masuk ke dalam Trash!</p>" +
                    "<div class='divider'></div>" +
                    "<p class='font-bold'>Layanan: " + layanan + "</p>" +
                    "<p class='font-bold'>Cucian: " + cucian + "</p>",
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
                        url: "{{ route('harga-jenis-layanan.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "cabang_id": cabang_id
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

        function restore_button(id, layanan, cucian) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan dipulihkan!</p>" +
                    "<div class='divider'></div>" +
                    "<p class='font-bold'>Layanan: " + layanan + "</p>" +
                    "<p class='font-bold'>Cucian: " + cucian + "</p>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Pulihkan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('harga-jenis-layanan.restore') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dipulihkan!',
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
                                title: 'Data gagal dipulihkan!',
                            })
                        }
                    });
                }
            })
        }

        function destroy_button(id, layanan, cucian) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data yang dihapus permanen tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<p class='font-bold'>Layanan: " + layanan + "</p>" +
                    "<p class='font-bold'>Cucian: " + cucian + "</p>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus Permanen',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('harga-jenis-layanan.destroy') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus permanen!',
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
                                title: 'Data gagal dihapus permanen!',
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection

@section('container')
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Modal Create --}}
            <input type="checkbox" id="create_modal" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box p-0 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 max-w-2xl overflow-hidden">
                    
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tambah {{ $title }}</h3>
                        <label for="create_modal" class="cursor-pointer p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-500 dark:text-slate-400">
                            <i class="ri-close-large-fill text-lg leading-none"></i>
                        </label>
                    </div>

                    <form action="{{ route('harga-jenis-layanan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-6 space-y-5">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                
                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Layanan'" /></span>
                                    </label>
                                    <select name="jenis_layanan_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                        <option disabled selected>Pilih Jenis Layanan!</option>
                                        @foreach ($jenisLayanan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("jenis_layanan_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Cucian'" /></span>
                                    </label>
                                    <select name="jenis_cucian_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                        <option disabled selected>Pilih Jenis Cucian!</option>
                                        @foreach ($jenisCucian as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("jenis_cucian_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Prioritas'" /></span>
                                    </label>
                                    <select name="prioritas_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                        <option disabled selected>Pilih Jenis Prioritas!</option>
                                        @foreach ($prioritas as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("prioritas_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Satuan'" /></span>
                                    </label>
                                    <select name="jenis_satuan" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                        <option disabled selected>Pilih Jenis Satuan!</option>
                                        @foreach ($jenisSatuanLayanan as $item)
                                            <option value="{{ $item->value }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenis_satuan') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full md:col-span-2 mt-2">
                                    <label class="label pt-0 pb-1">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Harga'" /></span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                        </div>
                                        <input type="number" min="0" step="0.01" name="harga" placeholder="0" class="input input-bordered w-full pl-11 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ old('harga') }}" required />
                                    </div>
                                    @error('harga') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <label for="create_modal" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">
                                Batal
                            </label>
                            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Akhir Modal Create --}}

            {{-- Awal Modal Show --}}
            <input type="checkbox" id="show_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box p-0 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 max-w-2xl overflow-hidden">
                    
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Detail {{ $title }}</h3>
                        <label for="show_button" class="cursor-pointer p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-500 dark:text-slate-400">
                            <i class="ri-close-large-fill text-lg leading-none"></i>
                        </label>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Jenis Layanan</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </label>
                                <input type="text" name="jenis_layanan_id" class="input input-bordered w-full bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly />
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Jenis Cucian</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </label>
                                <input type="text" name="jenis_cucian_id" class="input input-bordered w-full bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly />
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Prioritas</span>
                                    <span class="label-text-alt" id="loading_edit3"></span>
                                </label>
                                <input type="text" name="prioritas_id" class="input input-bordered w-full bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly />
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Jenis Satuan</span>
                                    <span class="label-text-alt" id="loading_edit5"></span>
                                </label>
                                <input type="text" name="jenis_satuan" class="input input-bordered w-full bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly />
                            </div>

                            <div class="form-control w-full md:col-span-2 mt-2">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-semibold text-blue-600/80 dark:text-blue-400/80 uppercase tracking-wider text-xs">Total Harga</span>
                                    <span class="label-text-alt" id="loading_edit4"></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold text-lg">Rp</span>
                                    </div>
                                    <input type="number" name="harga" class="input input-bordered w-full pl-12 py-6 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-bold text-xl cursor-not-allowed border-blue-200 dark:border-blue-800/50 focus:outline-none shadow-inner" readonly />
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                        <label for="show_button" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">
                            Tutup
                        </label>
                    </div>

                </div>
            </div>
            {{-- Akhir Modal Show --}}

            {{-- Awal Modal Edit --}}
            <input type="checkbox" id="edit_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box p-0 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 max-w-2xl overflow-hidden">
                    
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ubah {{ $title }}</h3>
                        <label for="edit_button" class="cursor-pointer p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-500 dark:text-slate-400">
                            <i class="ri-close-large-fill text-lg leading-none"></i>
                        </label>
                    </div>

                    <form action="{{ route('harga-jenis-layanan.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id">
                        
                        <div class="p-6 space-y-5">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                
                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1 flex justify-between items-center">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Layanan'" /></span>
                                        <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit1"></span>
                                    </label>
                                    <select id="jenis_layanan_select" name="jenis_layanan_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    </select>
                                    @error("jenis_layanan_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1 flex justify-between items-center">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Cucian'" /></span>
                                        <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit2"></span>
                                    </label>
                                    <select id="jenis_cucian_select" name="jenis_cucian_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    </select>
                                    @error("jenis_cucian_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1 flex justify-between items-center">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Prioritas'" /></span>
                                        <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit3"></span>
                                    </label>
                                    <select id="prioritas_select" name="prioritas_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    </select>
                                    @error("prioritas_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pt-0 pb-1 flex justify-between items-center">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Satuan'" /></span>
                                        <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit5"></span>
                                    </label>
                                    <select id="jenis_satuan_select" name="jenis_satuan" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    </select>
                                    @error('jenis_satuan') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full md:col-span-2 mt-2">
                                    <label class="label pt-0 pb-1 flex justify-between items-center">
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Harga'" /></span>
                                        <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit4"></span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                        </div>
                                        <input type="number" min="0" step="0.01" name="harga" placeholder="0" class="input input-bordered w-full pl-11 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required />
                                    </div>
                                    @error('harga') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <label for="edit_button" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">
                                Batal
                            </label>
                            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Akhir Modal Edit --}}

            {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Data</h3>
                            <label for="impor_modal" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('harga-jenis-layanan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

            {{-- Awal Tabel Harga Jenis Layanan --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
                
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900">
                    <h6 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h6>
                    
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        @if (empty($cabang?->deleted_at))
                            
                            <form action="{{ route('harga-jenis-layanan.export') }}" method="GET" class="inline-block w-full sm:w-auto">
                                @csrf
                                <input type="hidden" name="cabang" value="{{ auth()->user()->cabang?->slug }}" />
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">
                                    <i class="ri-download-2-line text-lg leading-none"></i> Ekspor
                                </button>
                            </form>

                            <label for="impor_modal" class="w-full sm:w-auto cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">
                                <i class="ri-upload-2-line text-lg leading-none"></i> Impor
                            </label>

                            <label for="create_modal" class="w-full sm:w-auto cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="ri-add-line text-lg leading-none"></i> Tambah
                            </label>

                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="myTable" class="w-full text-left border-collapse" style="width: 100%;">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis Layanan</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis Cucian</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Prioritas</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Satuan</th>
                                @role('owner')
                                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cabang</th>
                                @endrole
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat Pada</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                            @foreach ($hargaJenisLayanan as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ $item->nama_layanan }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->nama_cucian }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->prioritas->nama }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-white">
                                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->jenis_satuan }}
                                    </td>
                                    
                                    @role('owner')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                            {{ $item->cabang ? $item->cabang->nama : 'Tanpa Cabang' }}
                                        </td>
                                    @endrole
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d M Y") }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            <label for="show_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors tooltip" data-tip="Lihat Detail" onclick="return show_button('{{ $item->id }}')">
                                                <i class="ri-eye-line text-lg leading-none"></i>
                                            </label>
                                            
                                            @if (empty($cabang?->deleted_at))
                                                <label for="edit_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-amber-600 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-500/10 dark:hover:bg-amber-500/20 rounded-lg transition-colors tooltip" data-tip="Ubah Data" onclick="return edit_button('{{ $item->id }}')">
                                                    <i class="ri-pencil-fill text-lg leading-none"></i>
                                                </label>
                                                
                                                <label for="delete_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-red-600 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-500/10 dark:hover:bg-red-500/20 rounded-lg transition-colors tooltip" data-tip="Hapus Data" onclick="return delete_button('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama_layanan }}', '{{ $item->nama_cucian }}')">
                                                    <i class="ri-delete-bin-line text-lg leading-none"></i>
                                                </label>
                                            @endif
                                            
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Akhir Tabel Harga Jenis Layanan --}}

            {{-- Awal Tabel Harga Jenis Layanan Trash --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
                
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <h6 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        {{ $title }} Trash 
                        <span class="px-2.5 py-1 rounded-md bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-semibold tracking-wide uppercase">Data Dihapus</span>
                    </h6>
                </div>

                <div class="overflow-x-auto">
                    <table id="myTable1" class="w-full text-left border-collapse" style="width: 100%;">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis Layanan</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis Cucian</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Prioritas</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Satuan</th>
                                @role('owner')
                                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cabang</th>
                                @endrole
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dibuat Pada</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dihapus Pada</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                            @foreach ($hargaJenisLayananTrash as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ $item->nama_layanan }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->nama_cucian }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->prioritas->nama }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-white">
                                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->jenis_satuan }}
                                    </td>
                                    
                                    @role('owner')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                            {{ $item->cabang ? $item->cabang->nama : 'Tanpa Cabang' }}
                                        </td>
                                    @endrole
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d M Y") }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500 dark:text-red-400 font-medium">
                                        {{ Carbon\Carbon::parse($item->deleted_at)->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            <label for="show_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors tooltip" data-tip="Lihat Detail" onclick="return show_button('{{ $item->id }}')">
                                                <i class="ri-eye-line text-lg leading-none"></i>
                                            </label>
                                            
                                            @if (empty($cabang?->deleted_at))
                                                <label for="restore_button" class="cursor-pointer inline-flex items-center justify-center gap-1.5 px-3 py-2 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 rounded-lg transition-colors text-sm font-medium tooltip" data-tip="Pulihkan Data" onclick="return restore_button('{{ $item->id }}', '{{ $item->nama_layanan }}', '{{ $item->nama_cucian }}')">
                                                    <i class="ri-history-line text-lg leading-none"></i>
                                                </label>
                                                
                                                <label for="destroy_button" class="cursor-pointer inline-flex items-center justify-center gap-1.5 px-3 py-2 text-red-700 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-500/10 dark:hover:bg-red-500/20 rounded-lg transition-colors text-sm font-medium" onclick="return destroy_button('{{ $item->id }}', '{{ $item->nama_layanan }}', '{{ $item->nama_cucian }}')">
                                                    <i class="ri-delete-bin-line text-lg leading-none"></i>
                                                    <span>Permanen</span>
                                                </label>
                                            @endif
                                            
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Akhir Tabel Harga Jenis Layanan Trash --}}
        </div>
    </div>
@endsection
