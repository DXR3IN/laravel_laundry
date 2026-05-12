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
            $('#myTable2').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });
            $('#myTable3').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr',
                    },
                },
                order: [],
                pagingType: 'full_numbers',
            });
            $('#myTable4').DataTable({
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

        // Jenis Layanan
        function show_button_jenis_layanan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-layanan.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);
                    if (items[3]) {
                        $("input[name='for_gamis'][value='1']").prop("checked", true);
                        $("input[name='for_gamis'][value='0']").prop("checked", false);
                    } else {
                        $("input[name='for_gamis'][value='1']").prop("checked", false);
                        $("input[name='for_gamis'][value='0']").prop("checked", true);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                }
            });
        }

        function edit_button_jenis_layanan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-layanan.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);
                    if (items[3]) {
                        $("input[name='for_gamis'][value='1']").prop("checked", true);
                        $("input[name='for_gamis'][value='0']").prop("checked", false);
                    } else {
                        $("input[name='for_gamis'][value='1']").prop("checked", false);
                        $("input[name='for_gamis'][value='0']").prop("checked", true);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                }
            });
        }

        function delete_button_jenis_layanan(id, cabang_id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan masuk ke dalam Trash!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
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
                        url: "{{ route('jenis-layanan.delete') }}",
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

        // Layanan Tambahan
        function show_button_layanan_tambahan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('layanan-tambahan.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='nama']").val(items[1]);
                    $("input[name='harga']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function edit_button_layanan_tambahan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('layanan-tambahan.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);
                    $("input[name='harga']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function delete_button_layanan_tambahan(id, cabang_id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan masuk ke dalam Trash!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
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
                        url: "{{ route('layanan-tambahan.delete') }}",
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

        // Jenis Cucian
        function show_button_jenis_cucian(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-cucian.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function edit_button_jenis_cucian(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('jenis-cucian.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                }
            });
        }

        function delete_button_jenis_cucian(id, cabang_id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan masuk ke dalam Trash!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
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
                        url: "{{ route('jenis-cucian.delete') }}",
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

        // Harga Jenis Layanan
        function show_button_harga_jenis_layanan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);
            $("#loading_edit4").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('harga-jenis-layanan.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='harga']").val(items[1]);
                    $("input[name='jenis_satuan']").val(items[2]);
                    $("input[name='jenis_layanan_id']").val(items[9]);
                    $("input[name='jenis_cucian_id']").val(items[10]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                }
            });
        }

        function edit_button_harga_jenis_layanan(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);
            $("#loading_edit4").html(loading);

            $("select[id='jenis_layanan_select']").children().remove().end();
            $("select[id='jenis_cucian_select']").children().remove().end();

            $.ajax({
                type: "get",
                url: "{{ route('harga-jenis-layanan.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='harga']").val(items[1]);

                    $("select[id='jenis_layanan_select']").html(`
                        <option disabled>Pilih Jenis Layanan!</option>
                        @foreach ($jenisLayanan as $item)
                            <option value="{{ $item->id }}" {{ $item->id == `+ items[3] +` ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    `);

                    $("select[id='jenis_cucian_select']").html(`
                        <option disabled>Pilih Jenis Cucian!</option>
                        @foreach ($jenisCucian as $item)
                            <option value="{{ $item->id }}" {{ $item->id == `+ items[4] +` ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    `);

                    $("select[id='jenis_satuan_select']").html(`
                        <option disabled selected>Pilih Jenis Satuan!</option>
                        @foreach ($jenisSatuanLayanan as $item)
                            <option value="{{ $item->value }}" {{ $item->value == `+ items[2] +` ? 'selected' : '' }}>{{ $item->value }}</option>
                        @endforeach
                    `);

                    $("select[id='jenis_layanan_select'] option[value='" + items[3] + "']").attr("selected", true);
                    $("select[id='jenis_cucian_select'] option[value='" + items[4] + "']").attr("selected", true);
                    $("select[id='jenis_satuan_select'] option[value='" + items[2] + "']").attr("selected", true);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                }
            });
        }

        function delete_button_harga_jenis_layanan(id, cabang_id, layanan, cucian) {
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

        // Layanan Prioritas
        function show_button_layanan_prioritas(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit4").html(loading);
            $("#loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('layanan-prioritas.show') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);
                    $("input[name='harga']").val(items[3]);
                    $("input[name='prioritas']").val(items[4]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit4").html(loading);
                    $("#loading_edit5").html(loading);
                }
            });
        }

        function edit_button_layanan_prioritas(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit4").html(loading);
            $("#loading_edit5").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('layanan-prioritas.edit') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);
                    $("textarea[name='deskripsi']").val(items[2]);
                    $("input[name='harga']").val(items[3]);
                    $("input[name='prioritas']").val(items[4]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit4").html(loading);
                    $("#loading_edit5").html(loading);
                }
            });
        }

        function delete_button_layanan_prioritas(id, cabang_id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "<p>Data akan masuk ke dalam Trash!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
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
                        url: "{{ route('layanan-prioritas.delete') }}",
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
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6">
                    <div>
                        <h6 class="text-xl font-bold text-blue-700 dark:text-white">
                            {{ $cabang->nama }}
                        </h6>
                        @if ($cabang->deleted_at)
                            <div class="badge badge-error text-white">Cabang Non Aktif</div>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('layanan-cabang.trash', $cabang->slug) }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-1 inline-block cursor-pointer rounded-lg border border-solid border-primary dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-primary dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-history-fill"></i>
                            Trash
                        </a>
                        <a href="{{ route('layanan-cabang') }}" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-slate-500 dark:border-white bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-slate-500 dark:text-white shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                            <i class="ri-arrow-left-line"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Awal Jenis Layanan --}}
                {{-- Awal Modal Create --}}
                <input type="checkbox" id="create_modal_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                            <label for="create_modal_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-layanan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="cabang_slug" value="{{ $cabang->slug }}" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Layanan'" />
                                        </span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Layanan" class="input input-bordered w-full text-blue-700" value="{{ old('nama') }}" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">Deskripsi</span>
                                    </div>
                                    <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Create --}}

                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama Layanan</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Deskripsi</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <textarea name="deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500" readonly></textarea>
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Modal Edit --}}
                <input type="checkbox" id="edit_button_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                            <label for="edit_button_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-layanan.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="id" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Layanan'" />
                                        </span>
                                        <span class="label-text-alt" id="loading_edit1"></span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Layanan" class="input input-bordered w-full text-blue-700" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">Deskripsi</span>
                                        <span class="label-text-alt" id="loading_edit2"></span>
                                    </div>
                                    <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500"></textarea>
                                    @error('deskripsi')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Edit --}}

                {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Jenis Layanan</h3>
                            <label for="impor_modal_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-layanan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                    <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

                {{-- Awal Tabel Jenis Layanan --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Jenis Layanan</h6>
                        <div class="w-1/2 max-w-full flex-none px-3 text-right">
                            @if (!$cabang->deleted_at)
                                @role("owner")
                                    <label for="create_modal_jenis_layanan" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-add-fill"></i>
                                        Tambah
                                    </label>
                                    <label for="impor_modal_jenis_layanan" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-upload-2-line"></i>
                                        Impor
                                    </label>
                                @endrole
                                <form action="{{ route('jenis-layanan.export') }}" method="GET" enctype="multipart/form-data" class="inline-block">
                                    @csrf
                                    <label class="form-control w-full">
                                        <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden readonly />
                                    </label>
                                    <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-download-2-line"></i>
                                        Ekspor
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable" class="nowrap stripe mb-0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Layanan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jenisLayanan as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_jenis_layanan" class="btn btn-outline btn-info btn-sm" onclick="return show_button_jenis_layanan('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("owner")
                                                            <label for="edit_button_jenis_layanan" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button_jenis_layanan('{{ $item->id }}')">
                                                                <i class="ri-pencil-fill text-base"></i>
                                                            </label>
                                                            <label for="delete_button_jenis_layanan" class="btn btn-outline btn-error btn-sm" onclick="return delete_button_jenis_layanan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-delete-bin-line text-base"></i>
                                                            </label>
                                                        @endrole
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Akhir Tabel Jenis Layanan --}}
            {{-- Akhir Jenis Layanan --}}

            {{-- Awal Jenis Cucian --}}
                {{-- Awal Modal Create --}}
                <input type="checkbox" id="create_modal_jenis_cucian" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                            <label for="create_modal_jenis_cucian" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-cucian.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="cabang_slug" value="{{ $cabang->slug }}" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Cucian'" />
                                        </span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Cucian" class="input input-bordered w-full text-blue-700" value="{{ old('nama') }}" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">Deskripsi</span>
                                    </div>
                                    <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Create --}}

                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_jenis_cucian" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_jenis_cucian" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama Cucian</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Deskripsi</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <textarea name="deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500" readonly></textarea>
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Modal Edit --}}
                <input type="checkbox" id="edit_button_jenis_cucian" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                            <label for="edit_button_jenis_cucian" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-cucian.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="id" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Cucian'" />
                                        </span>
                                        <span class="label-text-alt" id="loading_edit1"></span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Cucian" class="input input-bordered w-full text-blue-700" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">Deskripsi</span>
                                        <span class="label-text-alt" id="loading_edit2"></span>
                                    </div>
                                    <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500"></textarea>
                                    @error('deskripsi')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Edit --}}

                {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal_jenis_cucian" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Jenis Cucian</h3>
                            <label for="impor_modal_jenis_cucian" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('jenis-cucian.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                    <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

                {{-- Awal Tabel Jenis Cucian --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Jenis Cucian</h6>
                        <div class="w-1/2 max-w-full flex-none px-3 text-right">
                            @if (!$cabang->deleted_at)
                                @role("owner")
                                    <label for="create_modal_jenis_cucian" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-add-fill"></i>
                                        Tambah
                                    </label>
                                    <label for="impor_modal_jenis_cucian" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-upload-2-line"></i>
                                        Impor
                                    </label>
                                @endrole
                                <form action="{{ route('jenis-cucian.export') }}" method="GET" enctype="multipart/form-data" class="inline-block">
                                    @csrf
                                    <label class="form-control w-full">
                                        <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden readonly />
                                    </label>
                                    <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-download-2-line"></i>
                                        Ekspor
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable1" class="nowrap stripe mb-0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Cucian
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jenisCucian as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_jenis_cucian" class="btn btn-outline btn-info btn-sm" onclick="return show_button_jenis_cucian('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("owner")
                                                            <label for="edit_button_jenis_cucian" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button_jenis_cucian('{{ $item->id }}')">
                                                                <i class="ri-pencil-fill text-base"></i>
                                                            </label>
                                                            <label for="delete_button_jenis_cucian" class="btn btn-outline btn-error btn-sm" onclick="return delete_button_jenis_cucian('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-delete-bin-line text-base"></i>
                                                            </label>
                                                        @endrole
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Akhir Tabel Jenis Cucian --}}
            {{-- Akhir Jenis Cucian --}}

            {{-- Awal Harga Jenis Layanan --}}
                {{-- Awal Modal Create --}}
                <input type="checkbox" id="create_modal_harga_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                            <label for="create_modal_harga_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('harga-jenis-layanan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="cabang_slug" value="{{ $cabang->slug }}" hidden>
                                <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                    <label class="form-control w-full lg:w-1/2">
                                        <div class="label">
                                            <span class="label-text font-semibold">
                                                <x-label-input-required :value="'Jenis Layanan'" />
                                            </span>
                                        </div>
                                        <select name="jenis_layanan_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                            <option disabled selected>Pilih Jenis Layanan!</option>
                                            @foreach ($jenisLayanan as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error("jenis_layanan_id")
                                            <div class="label">
                                                <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </label>
                                    <label class="form-control w-full lg:w-1/2">
                                        <div class="label">
                                            <span class="label-text font-semibold">
                                                <x-label-input-required :value="'Jenis Cucian'" />
                                            </span>
                                        </div>
                                        <select name="jenis_cucian_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                            <option disabled selected>Pilih Jenis Cucian!</option>
                                            @foreach ($jenisCucian as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error("jenis_cucian_id")
                                            <div class="label">
                                                <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </label>
                                </div>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Harga'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" value="{{ old('harga') }}" required />
                                    @error('harga')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Jenis Satuan'" />
                                        </span>
                                    </div>
                                    <select name="jenis_satuan" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                        <option disabled selected>Pilih Jenis Satuan!</option>
                                        @foreach ($jenisSatuanLayanan as $item)
                                            <option selected value="{{ $item->value }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenis_satuan')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Create --}}

                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_harga_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_harga_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold">Jenis Layanan</span>
                                        <span class="label-text-alt" id="loading_edit1"></span>
                                    </div>
                                    <input type="text" name="jenis_layanan_id" class="input input-bordered w-full text-blue-700" readonly />
                                </label>
                                <label class="form-control w-full lg:w-1/2">
                                    <div class="label">
                                        <span class="label-text font-semibold">Jenis Cucian</span>
                                        <span class="label-text-alt" id="loading_edit2"></span>
                                    </div>
                                    <input type="text" name="jenis_cucian_id" class="input input-bordered w-full text-blue-700" readonly />
                                </label>
                            </div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Harga</span>
                                    <span class="label-text-alt" id="loading_edit3"></span>
                                </div>
                                <input type="number" name="harga" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Jenis Satuan</span>
                                    <span class="label-text-alt" id="loading_edit4"></span>
                                </div>
                                <input type="text" name="jenis_satuan" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Modal Edit --}}
                <input type="checkbox" id="edit_button_harga_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                            <label for="edit_button_harga_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('harga-jenis-layanan.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="id" hidden>
                                <input type="text" name="cabang_slug" value="{{ $cabang->slug }}" hidden>
                                <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap">
                                    <label class="form-control w-full lg:w-1/2">
                                        <div class="label">
                                            <span class="label-text font-semibold">
                                                <x-label-input-required :value="'Jenis Layanan'" />
                                            </span>
                                            <span class="label-text-alt" id="loading_edit1"></span>
                                        </div>
                                        <select id="jenis_layanan_select" name="jenis_layanan_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                        </select>
                                        @error("jenis_layanan_id")
                                            <div class="label">
                                                <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </label>
                                    <label class="form-control w-full lg:w-1/2">
                                        <div class="label">
                                            <span class="label-text font-semibold">
                                                <x-label-input-required :value="'Jenis Cucian'" />
                                            </span>
                                            <span class="label-text-alt" id="loading_edit2"></span>
                                        </div>
                                        <select id="jenis_cucian_select" name="jenis_cucian_id" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                        </select>
                                        @error("jenis_cucian_id")
                                            <div class="label">
                                                <span class="label-text-alt text-sm text-error">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </label>
                                </div>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Harga'" />
                                        </span>
                                        <span class="label-text-alt" id="loading_edit3"></span>
                                    </div>
                                    <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" required />
                                    @error('harga')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Jenis Satuan'" />
                                        </span>
                                        <span class="label-text-alt" id="loading_edit4"></span>
                                    </div>
                                    <select id="jenis_satuan_select" name="jenis_satuan" class="select select-bordered text-base text-blue-700 dark:bg-slate-100" required>
                                    </select>
                                    @error('jenis_satuan')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Edit --}}

                {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal_harga_jenis_layanan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Harga Jenis Layanan</h3>
                            <label for="impor_modal_harga_jenis_layanan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('harga-jenis-layanan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                    <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

                {{-- Awal Tabel Harga Jenis Layanan --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Harga Jenis Layanan</h6>
                        <div class="w-1/2 max-w-full flex-none px-3 text-right">
                            @if (!$cabang->deleted_at)
                                @role("owner")
                                    <label for="create_modal_harga_jenis_layanan" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-add-fill"></i>
                                        Tambah
                                    </label>
                                    <label for="impor_modal_harga_jenis_layanan" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-upload-2-line"></i>
                                        Impor
                                    </label>
                                @endrole
                                <form action="{{ route('harga-jenis-layanan.export') }}" method="GET" enctype="multipart/form-data" class="inline-block">
                                    @csrf
                                    <label class="form-control w-full">
                                        <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden readonly />
                                    </label>
                                    <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-download-2-line"></i>
                                        Ekspor
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable2" class="nowrap stripe mb-3 w-full max-w-full border-collapse items-center align-top text-slate-500 dark:border-white/40" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Jenis Layanan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Jenis Cucian
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Harga
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Jenis Satuan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hargaJenisLayanan as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama_layanan }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama_cucian }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    Rp{{ number_format($item->harga, 2, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->jenis_satuan }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_harga_jenis_layanan" class="btn btn-outline btn-info btn-sm" onclick="return show_button_harga_jenis_layanan('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("owner")
                                                            <label for="edit_button_harga_jenis_layanan" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button_harga_jenis_layanan('{{ $item->id }}')">
                                                                <i class="ri-pencil-fill text-base"></i>
                                                            </label>
                                                            <label for="delete_button_harga_jenis_layanan" class="btn btn-outline btn-error btn-sm" onclick="return delete_button_harga_jenis_layanan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama_layanan }}', '{{ $item->nama_cucian }}')">
                                                                <i class="ri-delete-bin-line text-base"></i>
                                                            </label>
                                                        @endrole
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Akhir Tabel Harga Jenis Layanan --}}
            {{-- Akhir Harga Jenis Layanan --}}

            {{-- Awal Layanan Prioritas --}}
                {{-- Awal Modal Create --}}
                <input type="checkbox" id="create_modal_layanan_prioritas" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                            <label for="create_modal_layanan_prioritas" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('layanan-prioritas.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="cabang_slug" value="{{ $cabang->slug }}" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Layanan Prioritas'" />
                                        </span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Layanan Prioritas" class="input input-bordered w-full text-blue-700" value="{{ old('nama') }}" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">Deskripsi</span>
                                    </div>
                                    <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Harga'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" value="{{ old('harga') }}" required />
                                    @error('harga')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nilai Prioritas'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" step="1" name="prioritas" placeholder="Nilai Prioritas" class="input input-bordered w-full text-blue-700" value="{{ old('prioritas') }}" required />
                                    @error('prioritas')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Create --}}

                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_layanan_prioritas" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_layanan_prioritas" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama Layanan Prioritas</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Deskripsi</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <textarea name="deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500" readonly></textarea>
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Harga</span>
                                    <span class="label-text-alt" id="loading_edit4"></span>
                                </div>
                                <input type="number" name="harga" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nilai Prioritas</span>
                                    <span class="label-text-alt" id="loading_edit5"></span>
                                </div>
                                <input type="number" name="prioritas" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Modal Edit --}}
                <input type="checkbox" id="edit_button_layanan_prioritas" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                            <label for="edit_button_layanan_prioritas" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('layanan-prioritas.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="id" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Layanan Prioritas'" />
                                        </span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Layanan Prioritas" class="input input-bordered w-full text-blue-700" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">Deskripsi</span>
                                    </div>
                                    <textarea name="deskripsi" placeholder="Deskripsi" class="textarea textarea-bordered w-full text-base text-blue-500"></textarea>
                                    @error('deskripsi')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Harga'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" required />
                                    @error('harga')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nilai Prioritas'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" step="1" name="prioritas" placeholder="Nilai Prioritas" class="input input-bordered w-full text-blue-700" required />
                                    @error('prioritas')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Edit --}}

                {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal_layanan_prioritas" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Layanan Prioritas</h3>
                            <label for="impor_modal_layanan_prioritas" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('layanan-prioritas.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                    <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

                {{-- Awal Tabel Layanan Prioritas --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Layanan Prioritas</h6>
                        <div class="w-1/2 max-w-full flex-none px-3 text-right">
                            @if (!$cabang->deleted_at)
                                @role("owner")
                                    <label for="create_modal_layanan_prioritas" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-add-fill"></i>
                                        Tambah
                                    </label>
                                    <label for="impor_modal_layanan_prioritas" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-upload-2-line"></i>
                                        Impor
                                    </label>
                                @endrole
                                <form action="{{ route('layanan-prioritas.export') }}" method="GET" enctype="multipart/form-data" class="inline-block">
                                    @csrf
                                    <label class="form-control w-full">
                                        <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden readonly />
                                    </label>
                                    <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-download-2-line"></i>
                                        Ekspor
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable3" class="nowrap stripe mb-0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Layanan Prioritas
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nilai Prioritas
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($layananPrioritas as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->prioritas }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_layanan_prioritas" class="btn btn-outline btn-info btn-sm" onclick="return show_button_layanan_prioritas('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("owner")
                                                            <label for="edit_button_layanan_prioritas" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button_layanan_prioritas('{{ $item->id }}')">
                                                                <i class="ri-pencil-fill text-base"></i>
                                                            </label>
                                                            <label for="delete_button_layanan_prioritas" class="btn btn-outline btn-error btn-sm" onclick="return delete_button_layanan_prioritas('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-delete-bin-line text-base"></i>
                                                            </label>
                                                        @endrole
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Akhir Tabel Layanan Prioritas --}}
            {{-- Akhir Layanan Prioritas --}}

            {{-- Awal Layanan Tambahan --}}
                {{-- Awal Modal Create --}}
                <input type="checkbox" id="create_modal_layanan_tambahan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Tambah {{ $title }}</h3>
                            <label for="create_modal_layanan_tambahan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('layanan-tambahan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="cabang_slug" value="{{ $cabang->slug }}" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Layanan'" />
                                        </span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Layanan" class="input input-bordered w-full text-blue-700" value="{{ old('nama') }}" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Harga'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" value="{{ old('harga') }}" required />
                                    @error('harga')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Create --}}

                {{-- Awal Modal Show --}}
                <input type="checkbox" id="show_button_layanan_tambahan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                            <label for="show_button_layanan_tambahan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Nama Layanan</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </div>
                                <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Harga</span>
                                    <span class="label-text-alt" id="loading_edit2"></span>
                                </div>
                                <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" readonly />
                            </label>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Show --}}

                {{-- Awal Modal Edit --}}
                <input type="checkbox" id="edit_button_layanan_tambahan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Ubah {{ $title }}</h3>
                            <label for="edit_button_layanan_tambahan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('layanan-tambahan.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="text" name="id" hidden>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Nama Layanan'" />
                                        </span>
                                        <span class="label-text-alt" id="loading_edit1"></span>
                                    </div>
                                    <input type="text" name="nama" placeholder="Nama Layanan" class="input input-bordered w-full text-blue-700" required />
                                    @error('nama')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold">
                                            <x-label-input-required :value="'Harga'" />
                                        </span>
                                        <span class="label-text-alt" id="loading_edit2"></span>
                                    </div>
                                    <input type="number" min="0" step="0.01" name="harga" placeholder="Harga" class="input input-bordered w-full text-blue-700" required />
                                    @error('harga')
                                        <div class="label">
                                            <span class="label-text-alt text-error text-sm">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </label>
                                <button type="submit" class="btn btn-warning mt-3 w-full text-slate-700">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Edit --}}

                {{-- Awal Modal Impor --}}
                <input type="checkbox" id="impor_modal_layanan_tambahan" class="modal-toggle" />
                <div class="modal" role="dialog">
                    <div class="modal-box">
                        <div class="mb-3 flex justify-between">
                            <h3 class="text-lg font-bold">Impor Layanan Tambahan</h3>
                            <label for="impor_modal_layanan_tambahan" class="cursor-pointer">
                                <i class="ri-close-large-fill"></i>
                            </label>
                        </div>
                        <div>
                            <form action="{{ route('layanan-tambahan.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="form-control w-full">
                                    <input type="file" name="impor" placeholder="Impor Data" class="file-input file-input-bordered w-full text-blue-700" required />
                                    <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden required />
                                </label>
                                <button type="submit" class="btn btn-success mt-3 w-full text-white">Impor</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Akhir Modal Impor --}}

                {{-- Awal Tabel Layanan Tambahan --}}
                <div class="dark:bg-slate-850 dark:shadow-dark-xl relative mb-6 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid border-transparent bg-white bg-clip-border shadow-xl">
                    <div class="border-b-solid mb-0 flex items-center justify-between rounded-t-2xl border-b-0 border-b-transparent p-6 pb-3">
                        <h6 class="font-bold dark:text-white">Layanan Tambahan</h6>
                        <div class="w-1/2 max-w-full flex-none px-3 text-right">
                            @if (!$cabang->deleted_at)
                                @role("owner")
                                    <label for="create_modal_layanan_tambahan" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-emerald-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-emerald-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-add-fill"></i>
                                        Tambah
                                    </label>
                                    <label for="impor_modal_layanan_tambahan" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-upload-2-line"></i>
                                        Impor
                                    </label>
                                @endrole
                                <form action="{{ route('layanan-tambahan.export') }}" method="GET" enctype="multipart/form-data" class="inline-block">
                                    @csrf
                                    <label class="form-control w-full">
                                        <input type="text" name="cabang" value="{{ $cabang->slug }}" hidden readonly />
                                    </label>
                                    <button type="submit" class="bg-150 active:opacity-85 tracking-tight-rem bg-x-25 mb-0 inline-block cursor-pointer rounded-lg border border-solid border-purple-500 bg-transparent px-4 py-1 text-center align-middle text-sm font-bold leading-normal text-purple-500 shadow-none transition-all ease-in hover:-translate-y-px hover:opacity-75 md:px-8 md:py-2">
                                        <i class="ri-download-2-line"></i>
                                        Ekspor
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="flex-auto px-0 pb-2 pt-0">
                        <div class="overflow-x-auto p-0 px-6 pb-6">
                            <table id="myTable4" class="nowrap stripe mb-0" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Nama Layanan
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Harga
                                        </th>
                                        <th class="bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Created_at
                                        </th>
                                        <th class="rounded-tr bg-blue-500 text-xs font-bold uppercase text-white dark:text-white">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($layananTambahan as $item)
                                        <tr>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ $item->nama }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    Rp{{ number_format($item->harga, 2, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <p class="text-base font-semibold leading-tight text-slate-500 dark:text-slate-200">
                                                    {{ Carbon\Carbon::parse($item->created_at)->translatedFormat("d F Y") }}
                                                </p>
                                            </td>
                                            <td class="border-b border-slate-600 bg-transparent text-left align-middle">
                                                <div>
                                                    <label for="show_button_layanan_tambahan" class="btn btn-outline btn-info btn-sm" onclick="return show_button_layanan_tambahan('{{ $item->id }}')">
                                                        <i class="ri-eye-line text-base"></i>
                                                    </label>
                                                    @if (!$cabang->deleted_at)
                                                        @role("owner")
                                                            <label for="edit_button_layanan_tambahan" class="btn btn-outline btn-warning btn-sm" onclick="return edit_button_layanan_tambahan('{{ $item->id }}')">
                                                                <i class="ri-pencil-fill text-base"></i>
                                                            </label>
                                                            <label for="delete_button_layanan_tambahan" class="btn btn-outline btn-error btn-sm" onclick="return delete_button_layanan_tambahan('{{ $item->id }}', '{{ $item->cabang_id }}', '{{ $item->nama }}')">
                                                                <i class="ri-delete-bin-line text-base"></i>
                                                            </label>
                                                        @endrole
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Akhir Tabel Layanan Tambahan --}}
            {{-- Akhir Layanan Tambahan --}}
        </div>
    </div>
@endsection
