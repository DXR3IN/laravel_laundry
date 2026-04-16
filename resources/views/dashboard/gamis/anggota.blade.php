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

        function show_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-blue-500"></span>`;
            $("#loading_edit1").html(loading);
            $("#loading_edit2").html(loading);
            $("#loading_edit3").html(loading);
            $("#loading_edit4").html(loading);
            $("#loading_edit5").html(loading);
            $("#loading_edit6").html(loading);

            $.ajax({
                success: function(data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("input[name='nama']").val(items[1]);
                    $("input[name='ttl']").val(items[4] + ', ' + items[5]);
                    $("input[name='telepon']").val(items[6]);
                    $("textarea[name='alamat']").val(items[7]);

                    if (items[3] == "L") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", true);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", false);
                    } else if (items[3] == "P") {
                        $("input[name='jenis_kelamin'][value='L']").prop("checked", false);
                        $("input[name='jenis_kelamin'][value='P']").prop("checked", true);
                    }

                    if (items[2]) {
                        $("#foto-profile").html(`
                            <div class="avatar">
                                <div class="w-24 rounded-full">
                                    <img src="{{ asset("storage/` + items[2] + `") }}" alt="foto-profile" />
                                </div>
                            </div>
                        `);
                    } else {
                        $("#foto-profile").html(`
                            <div class="avatar">
                                <div class="w-24 rounded-full">
                                    <img src="{{ asset("img/home-decor-2.jpg") }}" alt="foto-profile" />
                                </div>
                            </div>
                        `);
                    }

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                    $("#loading_edit2").html(loading);
                    $("#loading_edit3").html(loading);
                    $("#loading_edit4").html(loading);
                    $("#loading_edit5").html(loading);
                    $("#loading_edit6").html(loading);
                }
            });
        }
    </script>
@endsection

@section("container")
    <div class="-mx-3 flex flex-wrap">
        <div class="w-full max-w-full flex-none px-3">
            {{-- Awal Modal Show --}}
            <input type="checkbox" id="show_button" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div class="mb-3 flex justify-between">
                        <h3 class="text-lg font-bold">Detail {{ $title }}</h3>
                        <label for="show_button" class="cursor-pointer">
                            <i class="ri-close-large-fill"></i>
                        </label>
                    </div>
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Foto Profile</span>
                                <span class="label-text-alt" id="loading_edit6"></span>
                            </div>
                            <div id="foto-profile"></div>
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Nama Lengkap</span>
                                <span class="label-text-alt" id="loading_edit1"></span>
                            </div>
                            <input type="text" name="nama" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <div class="mt-3 w-full max-w-md">
                            <div class="label">
                                <span class="label-text font-semibold">Jenis Kelamin</span>
                                <span class="label-text-alt" id="loading_edit2"></span>
                            </div>
                            <div class="rounded-lg border border-slate-300 px-3 py-2">
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700">Laki-laki</span>
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio-primary radio" disabled />
                                    </label>
                                </div>
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <span class="label-text text-blue-700">Perempuan</span>
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio-primary radio" disabled />
                                    </label>
                                </div>
                            </div>
                        </div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Tempat Tanggal Lahir</span>
                                <span class="label-text-alt" id="loading_edit3"></span>
                            </div>
                            <input type="text" name="ttl" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Telepon</span>
                                <span class="label-text-alt" id="loading_edit4"></span>
                            </div>
                            <input type="text" name="telepon" class="input input-bordered w-full text-blue-700" readonly />
                        </label>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Alamat</span>
                                <span class="label-text-alt" id="loading_edit5"></span>
                            </div>
                            <textarea name="alamat" class="textarea textarea-bordered w-full text-base text-blue-500" readonly></textarea>
                        </label>
                    </div>
                </div>
            </div>
            {{-- Akhir Modal Show --}}
        </div>
    </div>
@endsection
