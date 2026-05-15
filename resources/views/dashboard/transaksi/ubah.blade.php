@extends("dashboard.layouts.main")

@section("js")
    <script>
        // 1. FUNGSI AJAX & GLOBAL
        function ubahJenisCucian(jenisCucianId, selectPaketId, inputHargaId) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.create.ubahJenisCucian') }}",
                data: { "_token": "{{ csrf_token() }}", "jenisCucianId": jenisCucianId },
                success: function(data) {
                    let select = $("#" + selectPaketId);
                    select.empty().append(`<option disabled selected>Pilih Paket Layanan!</option>`);
                    
                    $.each(data, function(key, val) {
                        let namaLayanan = val.jenis_layanan ? val.jenis_layanan.nama : '-';
                        let namaPrioritas = val.layanan_prioritas ? val.layanan_prioritas.nama : 'Umum';
                        
                        select.append(`<option value="${val.id}" data-harga="${val.harga}">
                            ${namaLayanan} (${namaPrioritas})
                        </option>`);
                    });
                    
                    if (data.length === 1) {
                        select.prop('selectedIndex', 1);
                        setHarga(select[0], inputHargaId);
                    } else {
                        $("#" + inputHargaId).val(0); 
                    }
                }
            });
        }

        function setHarga(selectElement, inputHargaId) {
            let harga = $(selectElement).find(':selected').data('harga');
            $("#" + inputHargaId).val(harga);
        }

        function ubahLayananTambahan(layananTambahanId) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.create.ubahLayananTambahan', $cabang->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "layananTambahanId": layananTambahanId
                },
                success: function(data) {
                    $("input[name='total_biaya_layanan_tambahan']").val(data);
                }
            });
        }

        function totalBiaya(hargaLayanan, totalCucian, layananPrioritas, layananTambahan) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.create.hitungTotalBayar') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "hargaLayanan": hargaLayanan,
                    "totalCucian": totalCucian,
                    "layananTambahan": layananTambahan
                },
                success: function(data) {
                    $('input[name="total_biaya_layanan"]').val(data[0]);
                    $('input[name="total_biaya_prioritas"]').val(0); // Set 0
                    $('input[name="total_bayar_akhir"]').val(data[2]);
                    $('input[name="bayar"]').val(0);
                    $('input[name="kembalian"]').val(0);
                }
            });
        }

        // 2. FUNGSI UPDATE KE SERVER
        function updateTransaksiCabang() {
            // PENGAMAN JIKA LUPA KLIK CEK TOTAL BAYAR
            if (!$("input[name='total_biaya_layanan']").val() || $("input[name='total_biaya_layanan']").val() == "0") {
                return Swal.fire({
                    title: 'Peringatan',
                    text: 'Silakan klik tombol "Cek Total Bayar" (disket biru) terlebih dahulu untuk menghitung harga!',
                    icon: 'warning',
                    confirmButtonColor: '#6419E6',
                });
            }

            if (parseInt($('input[name="kembalian"]').val()) < 0 || $('input[name="bayar"]').val() <= 0) {
                return Swal.fire({
                    title: 'Gagal',
                    text: 'Uang yang dibayarkan kurang',
                    icon: 'error',
                    confirmButtonColor: '#6419E6',
                });
            }

            let detailTransaksi = $('input[name="detail_transaksi_id[]"]').map(function () { return $(this).val(); }).get();
            let cucian = $('select[name="jenis_cucian_id[]"]').map(function () { return $(this).val(); }).get();
            let hargaJenisLayanan = $('select[name="harga_jenis_layanan_id[]"]').map(function () { return $(this).val(); }).get();
            let totalCucian = $('input[name="total_cucian[]"]').map(function () { return $(this).val(); }).get();
            let statusCucian = $('select[name="status_cucian[]"]').map(function () { return $(this).val(); }).get();
            let layananTambahan = [];
            $('.cb-layanan-tambahan:checked').each(function() {
                layananTambahan.push($(this).val());
            });

            $.ajax({
                type: "post",
                url: "{{ route('transaksi.update', ['cabang' => $cabang->slug, 'transaksi' => $transaksi->id]) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "pelanggan_id": $("select[name='pelanggan_id']").val(),
                    "total_biaya_layanan": $("input[name='total_biaya_layanan']").val(),
                    "total_biaya_prioritas": 0, // Prioritas usang, kirim 0
                    "total_biaya_layanan_tambahan": $("input[name='total_biaya_layanan_tambahan']").val(),
                    "total_bayar_akhir": $("input[name='total_bayar_akhir']").val(),
                    "jenis_pembayaran": $("select[name='jenis_pembayaran']").val(),
                    "bayar": $("input[name='bayar']").val(),
                    "kembalian": $("input[name='kembalian']").val(),
                    "status": $("select[name='status']").val(),
                    "layanan_tambahan_id": layananTambahan,
                    "detail_transaksi_id": detailTransaksi,
                    "jenis_cucian_id": cucian,
                    "harga_jenis_layanan_id": hargaJenisLayanan,
                    "total_cucian": totalCucian,
                    "status_cucian": statusCucian,
                },
                success: function(data) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Transaksi Berhasil Diperbarui",
                        icon: "success",
                        confirmButtonColor: '#6419E6',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if ({{ $isJadwal ? 'true' : 'false' }}) {
                                return window.location.href = "{{ route('transaksi.jadwal') }}";
                            }
                            return window.location.href = "{{ route('transaksi') }}";
                        }
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Transaksi gagal diperbarui';
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        errorMessage = 'Validasi Gagal:\n';
                        for (let field in errors) {
                            errorMessage += '- ' + errors[field][0] + '\n';
                        }
                    } else if (xhr.status === 500) {
                        errorMessage = 'Error Database:\n' + xhr.responseJSON.error_detail;
                    }

                    Swal.fire({
                        title: 'Gagal',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonColor: '#6419E6',
                    });
                }
            });
        }

        // 3. JQUERY EVENT LISTENER
        $(document).ready(function() {
            let number = {{ $transaksi->detailTransaksi->count() + 1 }};

            $('#addLayanan').click(function (e) {
                e.preventDefault();
                $("#layananCart").append(`
                    <div class="w-full flex flex-wrap justify-center items-center gap-2 lg:flex-nowrap mb-2">
                        <label class="form-control w-full lg:w-1/4">
                            <div class="label"><span class="label-text font-semibold dark:text-slate-100">Jenis Cucian</span></div>
                            <select id="jenisCucian${number}" name="jenis_cucian_id[]" class="select select-bordered text-blue-700" onchange="ubahJenisCucian(this.value, 'paketLayanan${number}', 'hargaInput${number}')" required>
                                <option disabled selected>Pilih Cucian!</option>
                                @foreach ($cucian as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </label>
                        
                        <label class="form-control w-full lg:w-2/4">
                            <div class="label"><span class="label-text font-semibold dark:text-slate-100">Paket Layanan (Layanan + Prioritas)</span></div>
                            <select id="paketLayanan${number}" name="harga_jenis_layanan_id[]" class="select select-bordered text-blue-700" onchange="setHarga(this, 'hargaInput${number}')" required>
                                <option disabled selected>Pilih Paket!</option>
                            </select>
                        </label>

                        <label class="form-control w-full lg:w-1/4">
                            <div class="label"><span class="label-text font-semibold dark:text-slate-100">Harga Satuan</span></div>
                            <input type="text" id="hargaInput${number}" name="harga_satuan[]" placeholder="Harga" class="input input-bordered text-blue-700 bg-slate-200" readonly required />
                        </label>

                        <label class="form-control w-full lg:w-1/4">
                            <div class="label"><span class="label-text font-semibold dark:text-slate-100">Total (Kg/Unit)</span></div>
                            <input type="number" value="1" min="1" name="total_cucian[]" class="input input-bordered text-blue-700" required />
                        </label>
                    </div>
                    <div class="w-full flex flex-wrap justify-center items-start gap-2 lg:flex-nowrap mb-2">
                        <label class="form-control w-full lg:w-1/4">
                            <div class="label"><span class="label-text font-semibold dark:text-slate-100">Status Cucian</span></div>
                            <select name="status_cucian[]" class="select select-bordered text-blue-700 bg-slate-100 dark:bg-slate-800" readonly>
                                <option value="Baru" selected>Baru</option>
                            </select>
                        </label>
                    </div>
                `);
                number++;
            });

            $("#saveLayanan").click(function (e) {
                e.preventDefault();
                let hargaLayanan = $('input[name="harga_satuan[]"]').map(function () { return parseFloat($(this).val()) || 0; }).get();
                let totalCucian = $('input[name="total_cucian[]"]').map(function () { return parseFloat($(this).val()) || 0; }).get();
                let layananTambahan = parseFloat($("input[name='total_biaya_layanan_tambahan']").val()) || 0;

                totalBiaya(hargaLayanan, totalCucian, 0, layananTambahan);
            });

            $("#deleteLayanan").click(function (e) {
                e.preventDefault();
                let layananCart = document.getElementById("layananCart");
                if (layananCart.hasChildNodes()) {
                    layananCart.removeChild(layananCart.lastElementChild);
                    number--;
                }
            });

            $("input[name='bayar']").keyup(function (e) {
                let totalBayar = $("input[name='total_bayar_akhir']").val();
                $("input[name='kembalian']").val(this.value - totalBayar);
            });

            $(".pelanggan_id").select2();

            // Layanan Tambahan Event
            $('#radioTidakAda').on('change', function() {
                if($(this).is(':checked')) {
                    $('.cb-layanan-tambahan').prop('checked', false);
                    $("input[name='total_biaya_layanan_tambahan']").val(0); 
                    ubahLayananTambahan([]);
                }
            });

            $('.cb-layanan-tambahan').on('change', function() {
                let checkedCount = $('.cb-layanan-tambahan:checked').length;
                if (checkedCount > 0) {
                    $('#radioTidakAda').prop('checked', false);
                } else {
                    $('#radioTidakAda').prop('checked', true);
                }
                let selectedValues = [];
                $('.cb-layanan-tambahan:checked').each(function() {
                    selectedValues.push($(this).val());
                });
                ubahLayananTambahan(selectedValues);
            });
        });
    </script>
@endsection

@section("container")
    <div class="max-w-5xl mx-auto py-4">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Perbarui detail layanan, status, dan pembayaran transaksi.</p>
            </div>
            <div>
                @if ($isJadwal)
                    <a href="{{ route("transaksi.jadwal") }}" class="btn bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm w-full sm:w-auto">
                        <i class="ri-arrow-left-line text-lg leading-none"></i> Kembali
                    </a>
                @else
                    <a href="{{ route("transaksi") }}" class="btn bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm w-full sm:w-auto">
                        <i class="ri-arrow-left-line text-lg leading-none"></i> Kembali
                    </a>
                @endif
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-8">
            <form method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 md:p-8 space-y-10">
                    
                    <!-- SECTION 1: INFO NOTA & STATUS -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">1. Informasi Transaksi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Nota Layanan</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="ri-receipt-line text-slate-400"></i>
                                    </div>
                                    <input type="text" name="nota_layanan" class="input input-bordered w-full pl-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" value="{{ $transaksi->nota_layanan }}" readonly />
                                </div>
                            </div>
                            
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Nota Pelanggan</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="ri-ticket-2-line text-slate-400"></i>
                                    </div>
                                    <input type="text" name="nota_pelanggan" class="input input-bordered w-full pl-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" value="{{ $transaksi->nota_pelanggan }}" readonly />
                                </div>
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Status Pesanan'" /></span>
                                </label>
                                <select name="status" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    @foreach ($status as $item)
                                        <option value="{{ $item->value }}" @if ($item->value == $transaksi->status) selected @endif>{{ $item->value }}</option>
                                    @endforeach
                                </select>
                                @error("status") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: PELANGGAN -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">2. Data Pelanggan</h4>
                        <div class="form-control w-full">
                            <label class="label pt-0 pb-1.5">
                                <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Pelanggan'" /></span>
                            </label>
                            <select name="pelanggan_id" class="pelanggan_id select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                @foreach ($pelanggan as $item)
                                    <option value="{{ $item->id }}" @if ($item->id == $transaksi->pelanggan_id) selected @endif>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error("pelanggan_id") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- SECTION 3: LAYANAN UTAMA -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">3. Detail Cucian Utama</h4>
                        
                        <!-- Toolbar Aksi Layanan -->
                        <div class="flex flex-wrap items-center gap-2 mb-4 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700">
                            <button type="button" id="addLayanan" class="btn btn-sm bg-blue-100 hover:bg-blue-200 text-blue-700 border-0 dark:bg-blue-500/20 dark:hover:bg-blue-500/30 dark:text-blue-400">
                                <i class="ri-add-box-line text-base leading-none"></i> Tambah Item
                            </button>
                            <button type="button" id="deleteLayanan" class="btn btn-sm bg-red-100 hover:bg-red-200 text-red-700 border-0 dark:bg-red-500/20 dark:hover:bg-red-500/30 dark:text-red-400 tooltip tooltip-bottom" data-tip="Hapus item paling bawah">
                                <i class="ri-delete-back-2-line text-base leading-none"></i> Hapus Item
                            </button>
                            <div class="flex-grow"></div>
                            <button type="button" id="saveLayanan" class="btn btn-sm bg-emerald-100 hover:bg-emerald-200 text-emerald-700 border-0 dark:bg-emerald-500/20 dark:hover:bg-emerald-500/30 dark:text-emerald-400">
                                <i class="ri-calculator-line text-base leading-none"></i> Hitung Subtotal
                            </button>
                        </div>

                        <!-- Container Cart Loop -->
                        <div id="layananCart" class="space-y-4">
                            @foreach ($transaksi->detailTransaksi as $item => $value)
                                <div class="flex flex-col md:flex-row gap-4 items-start bg-slate-50 dark:bg-slate-800/30 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <input type="hidden" name="detail_transaksi_id[]" value="{{ $value->id }}">
                                    
                                    <div class="form-control w-full md:w-1/4">
                                        <label class="label pt-0 pb-1.5"><span class="label-text font-semibold text-slate-700 dark:text-slate-300">Jenis Cucian</span></label>
                                        <select id="jenisCucian{{ $item+1 }}" name="jenis_cucian_id[]" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" onchange="ubahJenisCucian(this.value, 'paketLayanan{{ $item+1 }}', 'hargaInput{{ $item+1 }}');" required>
                                            @foreach ($cucian as $itemCucian)
                                                <option value="{{ $itemCucian->id }}" @if ($itemCucian->id == $value->detailLayananTransaksi[0]->hargaJenisLayanan->jenisCucian->id) selected @endif>
                                                    {{ $itemCucian->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-control w-full md:w-1/4">
                                        <label class="label pt-0 pb-1.5"><span class="label-text font-semibold text-slate-700 dark:text-slate-300">Paket Layanan</span></label>
                                        <select id="paketLayanan{{ $item+1 }}" name="harga_jenis_layanan_id[]" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" onchange="setHarga(this, 'hargaInput{{ $item+1 }}')" required>
                                            @foreach ($hargaLayanan->where('jenis_cucian_id', $value->detailLayananTransaksi[0]->hargaJenisLayanan->jenisCucian->id) as $hjl)
                                                <option value="{{ $hjl->id }}" data-harga="{{ $hjl->harga }}" @if ($hjl->id == $value->detailLayananTransaksi[0]->harga_jenis_layanan_id) selected @endif>
                                                    {{ $hjl->jenisLayanan->nama ?? '-' }} ({{ $hjl->layananPrioritas->nama ?? 'Umum' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-2 pl-1">
                                            <i class="ri-time-line"></i> Selesai: {{ $value->estimasi_selesai ? \Carbon\Carbon::parse($value->estimasi_selesai)->format('d M, H:i') : '-' }}
                                        </span>
                                    </div>

                                    <div class="form-control w-full md:w-1/4">
                                        <label class="label pt-0 pb-1.5"><span class="label-text font-semibold text-slate-700 dark:text-slate-300">Harga Satuan</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-500 dark:text-slate-400 font-bold text-sm">Rp</span>
                                            </div>
                                            <input type="text" id="hargaInput{{ $item+1 }}" name="harga_satuan[]" class="input input-bordered w-full pl-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold cursor-not-allowed border-slate-200 dark:border-slate-700 focus:outline-none" value="{{ $value->harga_layanan_akhir }}" readonly required />
                                        </div>
                                    </div>

                                    <div class="form-control w-full md:w-1/6">
                                        <label class="label pt-0 pb-1.5"><span class="label-text font-semibold text-slate-700 dark:text-slate-300">Qty</span></label>
                                        <div class="join w-full">
                                            <input type="number" min="1" id="total_cucian{{ $item+1 }}" name="total_cucian[]" class="input input-bordered join-item w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $value->total_cucian }}" required />
                                        </div>
                                    </div>

                                    <div class="form-control w-full md:w-1/4">
                                        <label class="label pt-0 pb-1.5"><span class="label-text font-semibold text-slate-700 dark:text-slate-300">Status Cucian</span></label>
                                        <select name="status_cucian[]" class="select select-bordered w-full border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                            <option value="Baru" @if($value->status == 'Baru') selected @endif>Baru</option>
                                            <option value="Diproses" @if($value->status == 'Diproses') selected @endif>Diproses</option>
                                            <option value="Selesai" @if($value->status == 'Selesai') selected @endif>Selesai</option>
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- SECTION 4: LAYANAN TAMBAHAN -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">4. Layanan Tambahan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Pilih Layanan Tambahan</span>
                                </label>
                                <select id="layananTambahan" name="layanan_tambahan_id[]" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" onchange="return ubahLayananTambahan($(this).val());" multiple>
                                    @foreach ($layananTambahan as $item)
                                        <option value="{{ $item->id }}"
                                            @foreach ($transaksi->layananTambahanTransaksi as $layanan)
                                                @if ($item->id == $layanan->layanan_tambahan_id) selected @endif
                                            @endforeach
                                        >
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-xs text-slate-500 mt-1 block">Tahan <kbd class="px-1 bg-slate-200 rounded">Ctrl</kbd> atau <kbd class="px-1 bg-slate-200 rounded">Cmd</kbd> untuk memilih lebih dari satu.</span>
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Total Biaya Tambahan</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="total_biaya_layanan_tambahan" class="input input-bordered w-full pl-11 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold cursor-not-allowed focus:outline-none border-slate-200 dark:border-slate-700" value="{{ $transaksi->total_biaya_layanan_tambahan }}" readonly required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: RINGKASAN PEMBAYARAN -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">5. Ringkasan Pembayaran</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                            
                            <!-- Subtotal -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Subtotal Biaya Layanan Utama</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="total_biaya_layanan" class="input input-bordered w-full pl-11 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold cursor-not-allowed focus:outline-none border-slate-200 dark:border-slate-700" value="{{ $transaksi->total_biaya_layanan }}" readonly required />
                                </div>
                            </div>

                            <!-- Jenis Pembayaran -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Metode Pembayaran'" /></span>
                                </label>
                                <select name="jenis_pembayaran" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    @foreach ($jenisPembayaran as $item)
                                        <option value="{{ $item->value }}" @if ($item->value == $transaksi->jenis_pembayaran) selected @endif>{{ $item->value }}</option>
                                    @endforeach
                                </select>
                                @error("jenis_pembayaran") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Total Akhir (Highlighted) -->
                            <div class="form-control w-full md:col-span-2 mt-4">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider text-xs"><x-label-input-required :value="'Total Tagihan Keseluruhan'" /></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                        <span class="text-blue-700 dark:text-blue-300 font-bold text-xl">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="total_bayar_akhir" class="input input-bordered w-full pl-14 py-8 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-bold text-3xl cursor-not-allowed border-blue-200 dark:border-blue-800/50 shadow-inner focus:outline-none" value="{{ $transaksi->total_bayar_akhir }}" readonly required />
                                </div>
                            </div>

                            <!-- Nominal Bayar -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Nominal Dibayar'" /></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="bayar" class="input input-bordered w-full pl-11 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white font-semibold" value="{{ $transaksi->bayar }}" required />
                                </div>
                                @error("bayar") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kembalian -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1.5">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Uang Kembalian'" /></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-emerald-600 dark:text-emerald-500 font-bold">Rp</span>
                                    </div>
                                    <input type="text" name="kembalian" class="input input-bordered w-full pl-11 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-bold border-emerald-200 dark:border-emerald-800/50 cursor-not-allowed focus:outline-none shadow-inner" value="{{ $transaksi->kembalian }}" readonly required />
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Footer Card / Submit Action -->
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 md:px-8 py-5 border-t border-slate-200 dark:border-slate-800 flex flex-col items-end">
                    <button onclick="return updateTransaksiCabang()" type="button" class="btn bg-amber-500 hover:bg-amber-600 text-white border-0 w-full sm:w-1/3 shadow-sm text-base">
                        <i class="ri-save-3-line text-xl mr-1"></i> Perbarui Transaksi
                    </button>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center w-full sm:w-1/3">Pastikan data & total bayar sudah sesuai sebelum menyimpan.</p>
                </div>
            </form>
        </div>
    </div>
@endsection
