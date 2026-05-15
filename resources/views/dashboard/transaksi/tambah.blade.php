@extends("dashboard.layouts.main")

@section("js")
    <script>
        // ==========================================
        // 1. FUNGSI AJAX & GLOBAL (Harus di luar)
        // ==========================================
        function ubahJenisCucian(jenisCucianId, selectPaketId, inputHargaId) {
            $.ajax({
                type: "get",
                url: "{{ route('transaksi.create.ubahJenisCucian') }}",
                data: { "_token": "{{ csrf_token() }}", "jenisCucianId": jenisCucianId },
                success: function(data) {
                    let select = $("#" + selectPaketId);
                    select.empty().append(`<option disabled selected>Pilih Paket Layanan!</option>`);
                    
                    $.each(data, function(key, val) {
                        // Mengambil alias dari hasil JOIN Controller di atas
                        select.append(`<option value="${val.id}" data-harga="${val.harga}">
                            ${val.nama_layanan} (${val.nama_prioritas})
                        </option>`);
                    });
                    
                    // FITUR BARU: AUTO-SELECT (Jika paketnya cuma 1, otomatis dipilih & harga terisi)
                    if (data.length === 1) {
                        select.prop('selectedIndex', 1); // Pilih option urutan pertama
                        setHarga(select[0], inputHargaId); // Eksekusi pengisian harga
                    } else {
                        $("#" + inputHargaId).val(''); // Biarkan kosong jika banyak pilihan
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
                    "layananPrioritas": layananPrioritas,
                    "layananTambahan": layananTambahan
                },
                success: function(data) {
                    $('input[name="total_biaya_layanan"]').val(data[0]);
                    $('input[name="total_biaya_prioritas"]').val(data[1]);
                    $('input[name="total_bayar_akhir"]').val(data[2]);

                    $('input[name="bayar"]').val(0);
                    $('input[name="kembalian"]').val(0);
                }
            });
        }

        function storeTransaksiCabang() {
            if (parseInt($('input[name="kembalian"]').val()) < 0 || $('input[name="bayar"]').val() <= 0) {
                return Swal.fire({
                    title: 'Gagal',
                    text: 'Uang yang dibayarkan kurang',
                    icon: 'error',
                    confirmButtonColor: '#6419E6',
                    confirmButtonText: 'OK',
                });
            }

            let cucian = $('select[name="jenis_cucian_id[]"]').map(function () { return $(this).val(); }).get();
            let hargaJenisLayanan = $('select[name="harga_jenis_layanan_id[]"]').map(function () { return $(this).val(); }).get();
            let totalCucian = $('input[name="total_cucian[]"]').map(function () { return $(this).val(); }).get();
            let layananTambahan = $('input[name="layanan_tambahan_id[]"]:checked').map(function () { return $(this).val(); }).get();

            $.ajax({
                type: "post",
                url: "{{ route('transaksi.store') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "pelanggan_id": $("select[name='pelanggan_id']").val(),
                    "total_biaya_layanan": $("input[name='total_biaya_layanan']").val(),
                    "total_biaya_prioritas": $("input[name='total_biaya_prioritas']").val() || 0,
                    "total_biaya_layanan_tambahan": $("input[name='total_biaya_layanan_tambahan']").val(),
                    "total_bayar_akhir": $("input[name='total_bayar_akhir']").val(),
                    "jenis_pembayaran": $("select[name='jenis_pembayaran']").val(),
                    "bayar": $("input[name='bayar']").val(),
                    "kembalian": $("input[name='kembalian']").val(),
                    "layanan_tambahan_id": layananTambahan,
                    "jenis_cucian_id": cucian,
                    "harga_jenis_layanan_id": hargaJenisLayanan,
                    "total_cucian": totalCucian
                },
                success: function(data) {
                    Swal.fire({
                        title: "Berhasil",
                        text: "Transaksi Berhasil Ditambahkan",
                        icon: "success",
                        confirmButtonColor: '#6419E6',
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if ({{ $isJadwal ? 'true' : 'false' }}) {
                                return window.location.href = "{{ route('transaksi.jadwal') }}";
                            }
                            return window.location.href = "{{ route('transaksi') }}";
                        }
                    });
                },
                error: function(xhr) { // Ganti 'data' menjadi 'xhr'
                    let errorMessage = 'Transaksi gagal ditambahkan';

                    // 1. Tangkap error validasi (dari Controller)
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        errorMessage = 'Validasi Gagal:\n';
                        for (let field in errors) {
                            errorMessage += '- ' + errors[field][0] + '\n';
                        }
                    } 
                    // 2. Tangkap error sistem / database (dari Controller try-catch)
                    else if (xhr.status === 500) {
                        errorMessage = 'Error Database:\n' + xhr.responseJSON.error_detail;
                        console.error(xhr.responseJSON.error_detail); // Tulis error utuh di Console
                    }

                    // Tampilkan error aslinya ke kasir
                    Swal.fire({
                        title: 'Gagal',
                        text: errorMessage, // Pesan ini sekarang akan berisi penyebab aslinya
                        icon: 'error',
                        confirmButtonColor: '#6419E6',
                        confirmButtonText: 'OK',
                    });
                }
            });
        }


        // ==========================================
        // 2. JQUERY LOGIC (Saat halaman dimuat)
        // ==========================================
        $(document).ready(function() {
            
            let number = 1; // VARIABEL INI SANGAT PENTING

            $(".pelanggan_id").select2();

            // Event Tambah Layanan
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
                `);
                number++;
            });

            // Event Hapus Layanan
            $("#deleteLayanan").click(function (e) {
                e.preventDefault();
                let layananCart = document.getElementById("layananCart");
                if (layananCart.hasChildNodes()) {
                    layananCart.removeChild(layananCart.lastElementChild);
                    number--;
                }
            });

            // Event Cek Total Bayar
            $("#saveLayanan").click(function (e) {
                e.preventDefault();

                // Tambahkan parseFloat dan || 0 agar jika kosong/error tidak merusak hitungan (NaN)
                let hargaLayanan = $('input[name="harga_satuan[]"]').map(function () { 
                    return parseFloat($(this).val()) || 0; 
                }).get();
                
                let totalCucian = $('input[name="total_cucian[]"]').map(function () { 
                    return parseFloat($(this).val()) || 0; 
                }).get();
                
                let layananTambahan = parseFloat($("input[name='total_biaya_layanan_tambahan']").val()) || 0;

                totalBiaya(hargaLayanan, totalCucian, 0, layananTambahan);
            });

            // Event Kembalian
            $("input[name='bayar']").keyup(function (e) {
                let totalBayar = $("input[name='total_bayar_akhir']").val();
                $("input[name='kembalian']").val(this.value - totalBayar);
            });

            // Event Layanan Tambahan (Tidak Ada)
            $('#radioTidakAda').on('change', function() {
                if($(this).is(':checked')) {
                    $('.cb-layanan-tambahan').prop('checked', false);
                    $("input[name='total_biaya_layanan_tambahan']").val(0); 
                    ubahLayananTambahan([]);
                }
            });

            // Event Layanan Tambahan (Checkbox)
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

        // ==========================================
        // 3. SWEETALERT NOTIFICATION
        // ==========================================
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
    </script>
@endsection

@section("container")
    <div class="max-w-5xl mx-auto py-4">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih pelanggan dan tentukan detail layanan transaksi baru.</p>
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
                    
                    <!-- SECTION 1: PELANGGAN -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">1. Data Pelanggan</h4>
                        <div class="form-control w-full">
                            <label class="label pt-0 pb-1.5 flex justify-between items-end">
                                <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Pilih Pelanggan'" /></span>
                                <a href="{{ route('pelanggan') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Tambah Pelanggan Baru?</a>
                            </label>
                            <!-- Pastikan plugin select2 (jika ada) masih berfungsi di class .pelanggan_id ini -->
                            <select name="pelanggan_id" class="pelanggan_id select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                <option disabled selected>-- Cari dan Pilih Pelanggan --</option>
                                @foreach ($pelanggan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error("pelanggan_id") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- SECTION 2: LAYANAN UTAMA -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">2. Layanan Cucian Utama</h4>
                        
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
                        
                        <!-- Container Item Dinamis via JS -->
                        <div id="layananCart" class="space-y-4"></div>
                    </div>

                    <!-- SECTION 3: LAYANAN TAMBAHAN -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">3. Layanan Tambahan</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pilihan Ekstra -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4">
                                <div class="space-y-3">
                                    <label class="cursor-pointer flex items-center gap-3 p-2 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-colors">
                                        <input type="radio" id="radioTidakAda" name="status_layanan_tambahan" value="0" class="radio radio-primary radio-sm dark:border-slate-500" checked />
                                        <span class="label-text font-medium text-slate-700 dark:text-slate-200">Tidak ada tambahan</span>
                                    </label>

                                    <div class="h-px bg-slate-200 dark:bg-slate-700 mx-2"></div>

                                    @foreach ($layananTambahan as $item)
                                        <label class="cursor-pointer flex items-center gap-3 p-2 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-colors">
                                            <input type="checkbox" name="layanan_tambahan_id[]" value="{{ $item->id }}" class="checkbox checkbox-primary checkbox-sm cb-layanan-tambahan dark:border-slate-500" />
                                            <span class="label-text font-medium text-slate-700 dark:text-slate-200">{{ $item->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Biaya Layanan Tambahan -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300">Biaya Layanan Tambahan</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" min="0" value="0" name="total_biaya_layanan_tambahan" class="input input-bordered w-full pl-11 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold cursor-not-allowed focus:outline-none border-slate-200 dark:border-slate-700" readonly required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: PEMBAYARAN -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">4. Ringkasan Pembayaran</h4>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-end">
                            <div class="w-full flex flex-wrap justify-center gap-2 lg:flex-nowrap mb-2">
                                <label class="form-control w-full">
                                    <div class="label">
                                        <span class="label-text font-semibold dark:text-slate-100">
                                            <x-label-input-required :value="'Total Biaya Layanan'" />
                                        </span>
                                    </div>
                                    <input type="number" min="0" name="total_biaya_layanan" placeholder="Total Biaya Layanan" class="input input-bordered w-full text-blue-700 bg-slate-300" readonly required />
                                </label>
                            </div>
                            
                            <!-- Total Keseluruhan (Dibuat Mencolok) -->
                            <div class="form-control w-full lg:col-span-3 mb-2">
                                <label class="label pt-0 pb-1">
                                    <span class="label-text font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider text-xs"><x-label-input-required :value="'Total Tagihan Keseluruhan'" /></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                        <span class="text-blue-700 dark:text-blue-300 font-bold text-xl">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="total_bayar_akhir" placeholder="0" class="input input-bordered w-full pl-14 py-8 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-bold text-3xl cursor-not-allowed border-blue-200 dark:border-blue-800/50 shadow-inner focus:outline-none" readonly required />
                                </div>
                                @error("total_bayar_akhir") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Jenis Pembayaran -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Metode Pembayaran'" /></span>
                                </label>
                                <select name="jenis_pembayaran" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    <option disabled selected>-- Pilih Metode --</option>
                                    @foreach ($jenisPembayaran as $item)
                                        <option value="{{ $item->value }}">{{ $item->value }}</option>
                                    @endforeach
                                </select>
                                @error("jenis_pembayaran") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Nominal Bayar -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Nominal Dibayar (Customer)'" /></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" min="0" name="bayar" placeholder="0" class="input input-bordered w-full pl-12 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white font-semibold" required />
                                </div>
                                @error("bayar") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kembalian -->
                            <div class="form-control w-full">
                                <label class="label pt-0 pb-1">
                                    <span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Uang Kembalian'" /></span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-emerald-600 dark:text-emerald-500 font-bold">Rp</span>
                                    </div>
                                    <input type="text" name="kembalian" placeholder="0" class="input input-bordered w-full pl-12 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-bold border-emerald-200 dark:border-emerald-800/50 cursor-not-allowed focus:outline-none shadow-inner" readonly required />
                                </div>
                                @error("kembalian") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Footer Card / Submit Action -->
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 md:px-8 py-5 border-t border-slate-200 dark:border-slate-800 flex flex-col items-end">
                    <button onclick="return storeTransaksiCabang()" type="button" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-1/3 shadow-sm text-base">
                        <i class="ri-checkbox-circle-line text-xl mr-1"></i> Proses Transaksi
                    </button>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center w-full sm:w-1/3">Pastikan data & total bayar sudah sesuai.</p>
                </div>

            </form>
        </div>
    </div>
@endsection
