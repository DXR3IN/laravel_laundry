@extends("dashboard.layouts.main")

@section('js')
    <script>
        // Konfigurasi SweetAlert Modern
        const swalConfig = {
            confirmButtonColor: '#2563EB', // Blue-600
            confirmButtonText: 'OK',
            customClass: {
                popup: 'dark:bg-slate-800 dark:text-white',
                title: 'dark:text-white',
                htmlContainer: 'dark:text-slate-300'
            }
        };

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
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: { first: "Awal", last: "Akhir", next: "→", previous: "←" }
                }
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
                ...swalConfig
            });
        @endif

        @if (session()->has('error'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('error') }}',
                icon: 'error',
                ...swalConfig
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: '{{ $title }} Gagal Dibuat. Periksa kembali form Anda.',
                icon: 'error',
                ...swalConfig
            })
        @endif

        function show_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('pelanggan.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    // Input tetap sama, hanya tampilan CSS-nya yang read-only di HTML
                    $("input[name='nama']").val(items[1]);
                    $("input[name='telepon']").val(items[4]);
                    $("textarea[name='alamat']").val(items[5]);

                    if (items[3] == "L") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", true);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", false);
                    } else if (items[3] == "P") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", false);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", true);
                    }

                    // Loading effect end
                    $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4").empty();
                }
            });
        }

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('pelanggan.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);
                    $("input[name='telepon']").val(items[4]);
                    $("textarea[name='alamat']").val(items[5]);

                    if (items[3] == "L") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", true);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", false);
                    } else if (items[3] == "P") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", false);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", true);
                    }

                    // Loading effect end
                    $("#loading_edit1, #loading_edit2, #loading_edit3, #loading_edit4").empty();
                }
            });
        }

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p class='mb-4 text-slate-500'>Data pelanggan akan masuk ke dalam Trash!</p>" +
                      "<div class='p-3 bg-red-50 dark:bg-red-500/10 rounded-lg text-red-600 dark:text-red-400 font-medium'>Pelanggan: " + nama + "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444', // Red-500 untuk aksi destruktif
                cancelButtonColor: '#94A3B8', // Slate-400 untuk batal
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: swalConfig.customClass
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('pelanggan.delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Dihapus!',
                                text: 'Data berhasil dihapus.',
                                icon: 'success',
                                ...swalConfig
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
                                ...swalConfig
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection

@section('container')
    <div class="py-4">

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
                <form action="{{ route('pelanggan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Nama'" /></span>
                                </label>
                                <input type="text" name="nama" placeholder="Nama Lengkap" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ old('nama') }}" required />
                                @error('nama') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Kelamin'" /></span>
                                </label>
                                <div class="flex gap-4 items-center h-12">
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio radio-primary radio-sm dark:border-slate-500" required />
                                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Laki-laki</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio radio-primary radio-sm dark:border-slate-500" required />
                                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Perempuan</span>
                                    </label>
                                </div>
                                @error("jenis_kelamin") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Telepon'" /></span>
                                </label>
                                <input type="text" name="telepon" placeholder="Nomor Telepon/WA" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ old('telepon') }}" required />
                                @error('telepon') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Alamat Lengkap</span>
                                </label>
                                <textarea name="alamat" placeholder="Tulis alamat pelanggan..." rows="3" class="textarea textarea-bordered w-full text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">{{ old('alamat') }}</textarea>
                                @error('alamat') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <label for="create_modal" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">Batal</label>
                        <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">Simpan Data</button>
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
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="form-control w-full">
                            <label class="label pt-0 pb-1 flex justify-between items-center">
                                <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Nama Lengkap</span>
                                <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit1"></span>
                            </label>
                            <input type="text" name="nama" class="input input-bordered w-full bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-medium cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly />
                        </div>

                        <div class="form-control w-full">
                            <label class="label pt-0 pb-1 flex justify-between items-center">
                                <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Jenis Kelamin</span>
                                <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit2"></span>
                            </label>
                            <div class="flex gap-4 items-center h-12 px-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 cursor-not-allowed opacity-80">
                                <label class="flex items-center gap-2">
                                    <input type="radio" value="L" name="jenis_kelamin" class="radio radio-primary radio-sm grayscale cursor-not-allowed" disabled />
                                    <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" value="P" name="jenis_kelamin" class="radio radio-primary radio-sm grayscale cursor-not-allowed" disabled />
                                    <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-control w-full md:col-span-2">
                            <label class="label pt-0 pb-1 flex justify-between items-center">
                                <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Telepon / WA</span>
                                <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit3"></span>
                            </label>
                            <input type="text" name="telepon" class="input input-bordered w-full bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-medium cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly />
                        </div>

                        <div class="form-control w-full md:col-span-2">
                            <label class="label pt-0 pb-1 flex justify-between items-center">
                                <span class="label-text font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs">Alamat</span>
                                <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit4"></span>
                            </label>
                            <textarea name="alamat" rows="3" class="textarea textarea-bordered w-full text-base bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 font-medium cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" readonly></textarea>
                        </div>

                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                    <label for="show_button" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">Tutup</label>
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
                <form action="{{ route('pelanggan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Nama Lengkap'" /></span>
                                    <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit1"></span>
                                </label>
                                <input type="text" name="nama" placeholder="Nama Pelanggan" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required />
                                @error('nama') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Kelamin'" /></span>
                                    <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit2"></span>
                                </label>
                                <div class="flex gap-4 items-center h-12">
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio radio-primary radio-sm dark:border-slate-500" required />
                                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Laki-laki</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio radio-primary radio-sm dark:border-slate-500" required />
                                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Perempuan</span>
                                    </label>
                                </div>
                                @error("jenis_kelamin") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Telepon'" /></span>
                                    <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit3"></span>
                                </label>
                                <input type="text" name="telepon" placeholder="Nomor Telepon/WA" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required />
                                @error('telepon') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0 pb-1 flex justify-between items-center">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Alamat Lengkap</span>
                                    <span class="label-text-alt text-blue-500 font-medium animate-pulse" id="loading_edit4"></span>
                                </label>
                                <textarea name="alamat" placeholder="Tulis alamat pelanggan..." rows="3" class="textarea textarea-bordered w-full text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white"></textarea>
                                @error('alamat') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <label for="edit_button" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">Batal</label>
                        <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Akhir Modal Edit --}}

        {{-- Awal Modal Impor --}}
        <input type="checkbox" id="impor_modal" class="modal-toggle" />
        <div class="modal" role="dialog">
            <div class="modal-box p-0 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 max-w-md overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Impor Data Pelanggan</h3>
                    <label for="impor_modal" class="cursor-pointer p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-500 dark:text-slate-400">
                        <i class="ri-close-large-fill text-lg leading-none"></i>
                    </label>
                </div>
                <form action="{{ route('pelanggan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div class="form-control w-full">
                            <label class="label pt-0 pb-2"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Pilih File Excel/CSV</span></label>
                            <input type="file" name="impor" class="file-input file-input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                        <label for="impor_modal" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto cursor-pointer">Batal</label>
                        <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">Proses Impor</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Akhir Modal Impor --}}

        {{-- Awal Tabel Pelanggan --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
            
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900">
                <h6 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h6>
                
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    @role(['owner', 'manajer_laundry', 'pegawai_laundry'])
                        <label for="create_modal" class="w-full sm:w-auto cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <i class="ri-add-line text-lg leading-none"></i> Tambah Baru
                        </label>
                    @endrole
                    
                    @role(['owner', 'manajer_laundry'])
                        <label for="impor_modal" class="w-full sm:w-auto cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">
                            <i class="ri-upload-2-line text-lg leading-none"></i> Impor
                        </label>
                        <form action="{{ route('pelanggan.export') }}" method="GET" class="inline-block w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="cabang" value="{{ auth()->user()->cabang?->slug }}" />
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm">
                                <i class="ri-download-2-line text-lg leading-none"></i> Ekspor
                            </button>
                        </form>
                    @endrole
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="myTable" class="w-full text-left border-collapse" style="width: 100%;">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jenis Kelamin</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tgl Terdaftar</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        @foreach ($pelanggan as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                    {{ $item->nama }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    @if ($item->jenis_kelamin == 'L')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 text-xs font-medium">
                                            Laki-laki
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-pink-50 text-pink-700 dark:bg-pink-500/10 dark:text-pink-400 text-xs font-medium">
                                            Perempuan
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    {{ $item->telepon }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        
                                        <label for="show_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors tooltip" data-tip="Lihat Detail" onclick="return show_button('{{ $item->id }}')">
                                            <i class="ri-eye-line text-lg leading-none"></i>
                                        </label>
                                        
                                        @role(['owner', 'manajer_laundry', 'pegawai_laundry'])
                                            <label for="edit_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-amber-600 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-500/10 dark:hover:bg-amber-500/20 rounded-lg transition-colors tooltip" data-tip="Ubah Data" onclick="return edit_button('{{ $item->id }}')">
                                                <i class="ri-pencil-fill text-lg leading-none"></i>
                                            </label>
                                            
                                            <label for="delete_button" class="cursor-pointer inline-flex items-center justify-center p-2 text-red-600 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-500/10 dark:hover:bg-red-500/20 rounded-lg transition-colors tooltip" data-tip="Hapus Data" onclick="return delete_button('{{ $item->id }}', '{{ $item->nama }}')">
                                                <i class="ri-delete-bin-line text-lg leading-none"></i>
                                            </label>
                                        @endrole
                                        
                                    </div>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Akhir Tabel Pelanggan --}}

    </div>
@endsection