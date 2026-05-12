@extends("dashboard.layouts.main")

@section('js')
    <script>
        $(document).ready(function () {
            // Inisialisasi DataTable dengan gaya yang lebih bersih
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
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "→",
                        previous: "←"
                    }
                }
            });
        });

        @role(['manajer_laundry', 'pegawai_laundry', 'owner'])
            // Konfigurasi Warna Chart Modern (Blue-500 Tailwind)
            const chartColor = '#3b82f6'; 
            
            // Pendapatan Per Bulan
            if (document.querySelector("#chart-pendapatan-bulanan")) {
                let bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                let hasilBulan = [];
                @foreach ($pendapatanBulanan as $item)
                    hasilBulan.push({{ $item['hasil'] }});
                @endforeach

                let ctx1 = document.getElementById("chart-pendapatan-bulanan").getContext("2d");
                let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
                gradientStroke1.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
                gradientStroke1.addColorStop(0.2, 'rgba(59, 130, 246, 0.05)');
                gradientStroke1.addColorStop(0, 'rgba(59, 130, 246, 0)');
                
                new Chart(ctx1, {
                    type: "line",
                    data: {
                        labels: bulan,
                        datasets: [{
                            label: "Pendapatan (Rp)",
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: chartColor,
                            borderColor: chartColor,
                            backgroundColor: gradientStroke1,
                            borderWidth: 3,
                            fill: true,
                            data: hasilBulan,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        interaction: { intersect: false, mode: 'index' },
                        scales: {
                            y: {
                                grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5], color: '#e2e8f0' },
                                ticks: { display: true, padding: 10, color: '#64748b', font: { size: 11, family: "Inter" } }
                            },
                            x: {
                                grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
                                ticks: { display: true, color: '#64748b', padding: 10, font: { size: 11, family: "Inter" } }
                            },
                        },
                    },
                });
            }

            // Pendapatan Per Tahun
            if (document.querySelector("#chart-pendapatan-tahunan")) {
                let tahun = [];
                let hasilTahun = [];
                @foreach ($pendapatanTahunan as $item)
                    tahun.push({{ $item['tahun'] }});
                    hasilTahun.push({{ $item['hasil'] }});
                @endforeach

                let ctx2 = document.getElementById("chart-pendapatan-tahunan").getContext("2d");
                let gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
                gradientStroke2.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
                gradientStroke2.addColorStop(0.2, 'rgba(59, 130, 246, 0.05)');
                gradientStroke2.addColorStop(0, 'rgba(59, 130, 246, 0)');
                
                new Chart(ctx2, {
                    type: "line",
                    data: {
                        labels: tahun,
                        datasets: [{
                            label: "Pendapatan (Rp)",
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: chartColor,
                            borderColor: chartColor,
                            backgroundColor: gradientStroke2,
                            borderWidth: 3,
                            fill: true,
                            data: hasilTahun,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        interaction: { intersect: false, mode: 'index' },
                        scales: {
                            y: {
                                grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5], color: '#e2e8f0' },
                                ticks: { display: true, padding: 10, color: '#64748b', font: { size: 11, family: "Inter" } }
                            },
                            x: {
                                grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
                                ticks: { display: true, color: '#64748b', padding: 10, font: { size: 11, family: "Inter" } }
                            },
                        },
                    },
                });
            }
        @endrole
    </script>
@endsection

@section("container")
    <div class="py-4">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Dashboard</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ringkasan aktivitas dan pendapatan sistem Anda.</p>
            </div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm text-sm font-medium text-slate-600 dark:text-slate-300">
                <i class="ri-calendar-event-line text-blue-500 text-lg"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>
        </div>

        @role(['manajer_laundry', 'pegawai_laundry', 'owner'])
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex items-center justify-between group hover:border-blue-300 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                            {{ $userRole == 'owner' ? 'Cabang Aktif' : 'Cabang' }}
                        </p>
                        <h4 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">
                            {{ $userRole == 'owner' ? $jmlCabang : ($cabang ? $cabang->nama : 'Non Aktif') }}
                        </h4>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                        <i class="ri-store-2-line"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex items-center justify-between group hover:border-blue-300 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">User Aktif</p>
                        <h4 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $jmlUser }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl group-hover:scale-110 transition-transform">
                        <i class="ri-group-line"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex items-center justify-between group hover:border-blue-300 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">UMR <span class="capitalize">{{ $umr->regional }}</span> ({{ $umr->tahun }})</p>
                        <h4 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">Rp{{ number_format($umr->upah, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-2xl group-hover:scale-110 transition-transform">
                        <i class="ri-wallet-3-line"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 border-l-4 border-l-blue-500">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Baru</p>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $transaksiBaru }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 border-l-4 border-l-amber-500">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Proses</p>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $transaksiProses }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 border-l-4 border-l-teal-500">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Siap Ambil</p>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $transaksiSiapDiambil }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 border-l-4 border-l-indigo-500">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Jemput</p>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $transaksiPenjemputan }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 border-l-4 border-l-purple-500">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Antar</p>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $transaksiPengantaran }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 border-l-4 border-l-emerald-500">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Selesai</p>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white">{{ $transaksiSelesai }}</h4>
                </div>
            </div>
            
        @endrole

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white dark:bg-slate-900 gap-4">
                <h6 class="text-lg font-bold text-slate-900 dark:text-white">Transaksi Hari Ini</h6>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 rounded-lg font-semibold text-sm">
                    <i class="ri-money-dollar-circle-line text-lg"></i>
                    Pendapatan: Rp{{ number_format($pendapatanHari, 0, ',', '.') }}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="myTable" class="w-full text-left border-collapse" style="width:100%;">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Prioritas</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Bayar</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pegawai</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            @role('owner')
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cabang</th>
                            @endrole
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        @foreach ($jadwalLayanan as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    {{ \Carbon\Carbon::parse($item->waktu)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white font-medium">
                                    {{ $item->layananPrioritas->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white font-medium">
                                    Rp{{ number_format($item->total_bayar_akhir, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    {{ $item->pelanggan->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    @php $userRole = $item->pegawai->roles[0]->name; @endphp
                                    @if ($userRole == 'manajer_laundry')
                                        {{ $item->pegawai->manajer->first()->nama }}
                                    @elseif ($userRole == 'pegawai_laundry')
                                        {{ $item->pegawai->pegawai->first()->nama }}
                                    @elseif ($userRole == 'owner')
                                        {{ $item->pegawai->owner->first()->nama }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-kolom-status-transaksi :value="$item->status" />
                                </td>
                                @role('owner')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                        {{ $item->cabang_nama }}
                                    </td>
                                @endrole
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @role('owner')
                                        <a href="{{ route("transaksi.owner.view", ['cabang' => $item->cabang_slug, 'transaksi' => $item->id, 'isJadwal' => true]) }}" class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors tooltip" data-tip="Lihat Detail">
                                            <i class="ri-eye-line text-lg leading-none"></i>
                                        </a>
                                    @endrole
                                    @role(['manajer_laundry', 'pegawai_laundry'])
                                        <a href="{{ route("transaksi.view", ['transaksi' => $item->id, 'isJadwal' => true]) }}" class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 rounded-lg transition-colors tooltip" data-tip="Lihat Detail">
                                            <i class="ri-eye-line text-lg leading-none"></i>
                                        </a>
                                    @endrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @role(['manajer_laundry', 'pegawai_laundry', 'owner'])
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                        <h6 class="text-base font-bold text-slate-900 dark:text-white">Pendapatan Bulanan <span class="text-blue-500">({{ \Carbon\Carbon::now()->format('Y') }})</span></h6>
                    </div>
                    <div class="p-6">
                        <div class="h-[300px]">
                            <canvas id="chart-pendapatan-bulanan"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                        <h6 class="text-base font-bold text-slate-900 dark:text-white">Pendapatan Tahunan</h6>
                    </div>
                    <div class="p-6">
                        <div class="h-[300px]">
                            <canvas id="chart-pendapatan-tahunan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

    </div>
@endsection