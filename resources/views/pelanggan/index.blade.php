<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Top Laundry' }}</title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo-laundry-simokerto.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo-laundry-simokerto.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    @vite(["resources/css/app.css", "resources/js/app.js"])

    <style>
        :root {
            --cream: #F5F0E8;
            --ink: #141010;
            --ink-muted: #5A524A;
            --teal: #0E6B5E;
            --teal-light: #E4F0EE;
            --gold: #C8A96E;
            --warm-white: #FDFAF5;
        }

        body {
            background: var(--warm-white);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            overflow-x: hidden;
        }

        /* --- NAV --- */
        .site-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 1.5rem 0;
            transition: background 0.4s, padding 0.4s, border-color 0.4s;
            border-bottom: 1px solid transparent;
        }
        .site-nav.scrolled {
            background: rgba(253, 250, 245, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem 0;
            border-bottom-color: rgba(20, 16, 16, 0.08);
        }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.35rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            letter-spacing: 0.03em;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .site-nav.scrolled .nav-logo { color: var(--ink); }
        .nav-logo-dot {
            width: 6px; height: 6px;
            background: var(--gold);
            border-radius: 50%;
            display: inline-block;
        }
        .nav-links { display: flex; gap: 2.5rem; }
        .nav-links a {
            font-size: 0.8rem;
            font-weight: 400;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.25s;
        }
        .site-nav.scrolled .nav-links a { color: var(--ink-muted); }
        .nav-links a:hover { color: #fff; }
        .site-nav.scrolled .nav-links a:hover { color: var(--teal); }
        .nav-cta {
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--ink);
            background: var(--gold);
            padding: 0.6rem 1.4rem;
            border-radius: 2px;
            text-decoration: none;
            transition: background 0.25s, color 0.25s;
        }
        .site-nav.scrolled .nav-cta { background: var(--teal); color: #fff; }
        .nav-cta:hover { opacity: 0.85; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .nav-logo { color: #fff !important; }
            .site-nav.scrolled .nav-logo { color: var(--ink) !important; }
        }

        /* --- HERO CUSTOM ANIMATIONS --- */
        @keyframes pulse-ring {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.04); opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        @keyframes scroll-drop {
            0% { transform: scaleY(0); transform-origin: top; }
            50% { transform: scaleY(1); transform-origin: top; }
            51% { transform: scaleY(1); transform-origin: bottom; }
            100% { transform: scaleY(0); transform-origin: bottom; }
        }

        /* --- ABOUT & OTHER SECTIONS --- */
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--teal);
            margin-bottom: 1.5rem;
        }
        .section-tag::before {
            content: '';
            display: block;
            width: 1.5rem;
            height: 1px;
            background: var(--teal);
        }

        /* Fade-in on scroll */
        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: none;
        }
    </style>
</head>

<body>

    {{-- ======= NAV ======= --}}
    <nav class="site-nav" id="mainNav">
        <div class="nav-inner">
            <a href="#home" class="nav-logo">
                <span class="nav-logo-dot"></span>
                {{ config('app.name') }}
            </a>
            <div class="nav-links">
                <a href="#home">Beranda</a>
                <a href="#tentang">Tentang Kami</a>
                <a href="#cekTransaksi">Cek Transaksi</a>
            </div>
            <a href="#cekTransaksi" class="nav-cta">Cek Status</a>
        </div>
    </nav>

    <main>

        {{-- ======= HERO (REDESIGNED LAYOUT) ======= --}}
        <section id="home" class="relative min-h-screen bg-[#0D1A15] flex items-center justify-center pt-24 pb-16 lg:pt-0 overflow-hidden">
            <!-- Subtle grid texture -->
            <div class="absolute inset-0 pointer-events-none opacity-20" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>

            <!-- Content Grid Container -->
            <div class="max-w-7xl mx-auto px-6 w-full relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-8 items-center">
                
                <!-- Left: Text Content -->
                <div class="max-w-xl mx-auto lg:mx-0 text-center lg:text-left">
                    <div class="inline-flex items-center gap-3 mb-6">
                        <div class="hidden lg:block w-8 h-[1px] bg-[#C8A96E]"></div>
                        <span class="text-[#C8A96E] text-[0.7rem] font-medium tracking-[0.2em] uppercase">Layanan Laundry Premium</span>
                    </div>
                    
                    <h1 class="font-serif text-[3.5rem] leading-[1.1] md:text-6xl lg:text-[4.5rem] font-light text-white mb-6">
                        Bersih adalah<br>
                        bentuk <em class="italic text-[#C8A96E]">keanggunan</em><br>
                        yang sunyi.
                    </h1>
                    
                    <p class="text-white/50 text-base md:text-lg leading-relaxed mb-10 mx-auto lg:mx-0 max-w-md">
                        Kami merawat pakaian Anda dengan keseriusan penuh — dari setiap serat hingga wangi akhir yang membekas.
                    </p>
                    
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6">
                        <a href="#cekTransaksi" class="bg-[#C8A96E] text-[#141010] px-8 py-3.5 text-xs font-semibold tracking-[0.1em] uppercase hover:bg-white transition-colors flex items-center gap-2 rounded-sm">
                            Lacak Cucian <i class="ri-arrow-right-line text-lg leading-none"></i>
                        </a>
                        <a href="#tentang" class="text-white/60 hover:text-white px-4 py-3.5 text-xs font-medium tracking-[0.1em] uppercase transition-colors flex items-center gap-2">
                            Tentang Kami <i class="ri-arrow-down-line text-lg leading-none"></i>
                        </a>
                    </div>
                </div>

                <!-- Right: Visuals -->
                <div class="relative w-full h-[400px] lg:h-[600px] flex items-center justify-center hidden md:flex">
                    <!-- Concentric Circles -->
                    <div class="absolute w-[300px] h-[300px] lg:w-[500px] lg:h-[500px] border border-[#C8A96E]/20 rounded-full" style="animation: pulse-ring 8s ease-in-out infinite;"></div>
                    <div class="absolute w-[220px] h-[220px] lg:w-[380px] lg:h-[380px] border border-[#C8A96E]/20 rounded-full" style="animation: pulse-ring 8s ease-in-out infinite 1s;"></div>
                    <div class="absolute w-[150px] h-[150px] lg:w-[260px] lg:h-[260px] border border-[#C8A96E]/20 bg-[#C8A96E]/5 rounded-full" style="animation: pulse-ring 8s ease-in-out infinite 2s;"></div>

                    <!-- Center Infinite Text -->
                    <div class="relative z-10 flex flex-col items-center text-center mt-6">
                        <span class="font-serif text-8xl lg:text-[9rem] font-light text-white/5 leading-none tracking-tighter">∞</span>
                        <span class="text-[#C8A96E]/80 text-[0.65rem] lg:text-[0.7rem] tracking-[0.2em] uppercase mt-1">Kepercayaan Pelanggan</span>
                    </div>

                    <!-- Floating Badges -->
                    <div class="absolute top-[10%] lg:top-[20%] right-[5%] bg-white/5 backdrop-blur-md border border-white/10 px-5 py-4 rounded-sm z-20" style="animation: float 6s ease-in-out infinite;">
                        <span class="block text-[0.65rem] tracking-[0.15em] uppercase text-white/40 mb-1">Estimasi Selesai</span>
                        <span class="block font-serif text-xl lg:text-2xl font-semibold text-white">2 – 3 Hari</span>
                    </div>

                    <div class="absolute bottom-[10%] lg:bottom-[20%] left-[5%] bg-white/5 backdrop-blur-md border border-white/10 px-5 py-4 rounded-sm z-20" style="animation: float 6s ease-in-out infinite 2s;">
                        <span class="block text-[0.65rem] tracking-[0.15em] uppercase text-white/40 mb-1">Status Layanan</span>
                        <span class="block font-serif text-xl lg:text-2xl font-semibold text-white">Aktif 24 Jam</span>
                    </div>
                </div>
            </div>

            <!-- Scroll Hint -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50 z-20 hidden md:flex">
                <div class="w-[1px] h-[40px] bg-gradient-to-b from-[#C8A96E] to-transparent" style="animation: scroll-drop 2s ease-in-out infinite;"></div>
                <span class="text-[0.65rem] tracking-[0.15em] text-white/50 uppercase">Scroll</span>
            </div>
        </section>

        {{-- ======= FEATURES STRIP ======= --}}
        <div class="bg-[var(--teal)]">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10">
                <div class="flex items-start gap-5 p-8 lg:p-10 hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-white/10 rounded text-white/90 text-xl">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium tracking-wide text-white mb-1.5">Kualitas Terjamin</h3>
                        <p class="text-[0.8rem] leading-relaxed text-white/60">Peralatan modern dan deterjen ramah lingkungan untuk hasil sempurna.</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 p-8 lg:p-10 hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-white/10 rounded text-white/90 text-xl">
                        <i class="ri-timer-flash-line"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium tracking-wide text-white mb-1.5">Cepat & Tepat Waktu</h3>
                        <p class="text-[0.8rem] leading-relaxed text-white/60">Ketepatan waktu adalah bagian dari janji layanan kami kepada Anda.</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 p-8 lg:p-10 hover:bg-white/5 transition-colors">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-white/10 rounded text-white/90 text-xl">
                        <i class="ri-heart-3-line"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium tracking-wide text-white mb-1.5">Kontribusi Sosial</h3>
                        <p class="text-[0.8rem] leading-relaxed text-white/60">Sebagian pendapatan kami dialirkan untuk keluarga yang membutuhkan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======= ABOUT ======= --}}
        <section id="tentang" class="bg-[var(--warm-white)] py-32">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="fade-up">
                    <span class="section-tag">Tentang Kami</span>
                    <h2 class="font-serif text-4xl md:text-5xl font-light text-[var(--ink)] max-w-2xl leading-[1.2]">
                        Lebih dari sekadar mencuci —<br>
                        <em class="italic text-[var(--teal)]">sebuah misi yang bermakna.</em>
                    </h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 items-center mt-16 fade-up">
                    <div class="lg:col-span-7">
                        <p class="text-[0.95rem] leading-[1.9] text-[var(--ink-muted)] mb-5">
                            Bisnis layanan pencucian kami menyajikan pelayanan membersihkan cucian secara menyeluruh, mencakup tahap mencuci hingga menyetrika, dan mengembalikan cucian kepada pelanggan dalam keadaan bersih dan harum.
                        </p>
                        <p class="text-[0.95rem] leading-[1.9] text-[var(--ink-muted)]">
                            Di Laundry kami, kami percaya bahwa misi tidak berhenti di cucian yang bersih. Kami memiliki satu tujuan mulia — membantu mensejahterakan lingkungan sekitar melalui setiap layanan yang kami berikan.
                        </p>
                        <div class="flex items-center gap-4 mt-10 pt-10 border-t border-[var(--ink)]/10">
                            <div class="flex-1 h-[1px] bg-[var(--ink)]/10"></div>
                            <span class="text-[0.72rem] tracking-[0.15em] uppercase text-[var(--ink-muted)] whitespace-nowrap">{{ config('app.name') }}</span>
                            <div class="flex-1 h-[1px] bg-[var(--ink)]/10"></div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 relative">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-[var(--gold)] rounded-full flex flex-col items-center justify-center z-20">
                            <span class="font-serif text-3xl font-semibold text-[var(--ink)] leading-none">10+</span>
                            <span class="text-[0.55rem] font-medium tracking-[0.1em] uppercase text-[var(--ink)]/70 text-center mt-1">Tahun<br>Pengalaman</span>
                        </div>
                        <div class="relative rounded-sm overflow-hidden group">
                            <img src="{{ asset('img/carousel-1.jpg') }}" alt="Proses Laundry" class="w-full h-[500px] object-cover filter saturate-90 transition-transform duration-700 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0D1A15]/60 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <p class="font-serif text-lg italic text-white/90 leading-relaxed">"Setiap helai kain yang kembali harum adalah kebanggaan kami."</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 border border-[var(--ink)]/10 rounded-sm overflow-hidden mt-24 fade-up divide-y md:divide-y-0 md:divide-x divide-[var(--ink)]/10">
                    <div class="p-10 text-center">
                        <span class="font-serif text-5xl font-light text-[var(--ink)] block leading-none mb-3">{{ floor($totalPelanggan) }}+</span>
                        <span class="text-[0.72rem] tracking-[0.15em] uppercase text-[var(--ink-muted)]">Pelanggan Aktif</span>
                    </div>
                    <div class="p-10 text-center">
                        <span class="font-serif text-5xl font-light text-[var(--ink)] block leading-none mb-3">{{ floor($totalJam) }} Jam</span>
                        <span class="text-[0.72rem] tracking-[0.15em] uppercase text-[var(--ink-muted)]">Layanan Ekspres</span>
                    </div>
                    <div class="p-10 text-center">
                        <span class="font-serif text-5xl font-light text-[var(--ink)] block leading-none mb-3">{{ $totalCabang }}</span>
                        <span class="text-[0.72rem] tracking-[0.15em] uppercase text-[var(--ink-muted)]">Cabang</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======= CEK TRANSAKSI ======= --}}
        <section id="cekTransaksi" class="bg-[var(--cream)] py-36 relative overflow-hidden">
            <div class="absolute -top-24 -right-48 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(14,107,94,0.07)_0%,transparent_70%)] pointer-events-none"></div>
            
            <div class="max-w-3xl mx-auto px-6 relative z-10">
                <div class="text-center mb-14 fade-up">
                    <div class="flex justify-center mb-4"><span class="section-tag m-0">Lacak Pesanan</span></div>
                    <h2 class="font-serif text-4xl md:text-5xl font-light text-[var(--ink)] mb-4 leading-tight">Cek Status Laundry<br>Anda Sekarang</h2>
                    <p class="text-[0.9rem] leading-[1.8] text-[var(--ink-muted)]">Masukkan nomor nota Anda untuk melihat detail dan progres pengerjaan cucian secara real-time.</p>
                </div>

                <div class="fade-up">
                    <div class="bg-white border border-[var(--ink)]/10 rounded-sm flex items-stretch overflow-hidden shadow-[0_2px_20px_rgba(20,16,16,0.06)] focus-within:border-[var(--teal)] focus-within:shadow-[0_4px_30px_rgba(14,107,94,0.12)] transition-all duration-300">
                        <div class="flex items-center px-5 text-[var(--ink-muted)] shrink-0">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                        <input
                            type="text"
                            name="nota"
                            class="flex-1 border-none outline-none font-sans text-[0.92rem] font-light text-[var(--ink)] bg-transparent py-4 min-w-0 placeholder:text-[var(--ink-muted)]/50 focus:ring-0"
                            placeholder="Masukkan nomor nota, mis. NOTA-12345"
                            autocomplete="off"
                        />
                        <button type="button" name="cekTansaksi" class="border-none cursor-pointer bg-[var(--teal)] hover:bg-[#0a5449] text-white font-sans text-[0.75rem] font-medium tracking-[0.12em] uppercase px-8 shrink-0 transition-colors flex items-center gap-2">
                            <i class="ri-search-2-line"></i> <span class="hidden sm:inline">Cek</span>
                        </button>
                    </div>
                </div>

                <div id="hasilCek"></div>
            </div>
        </section>

    </main>

    {{-- ======= FOOTER ======= --}}
    <footer class="bg-[var(--ink)] pt-20 pb-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12 pb-12 border-b border-white/10">
                <div>
                    <div class="font-serif text-3xl font-light text-white flex items-center gap-3">
                        <span class="w-1.5 h-1.5 bg-[var(--gold)] rounded-full block"></span>
                        {{ config('app.name') }}
                    </div>
                    <p class="text-[0.78rem] tracking-wide text-white/35 mt-3 max-w-xs leading-[1.7]">
                        Layanan laundry profesional dengan misi sosial yang nyata untuk masyarakat sekitar.
                    </p>
                </div>
                <nav class="flex gap-10">
                    <a href="#home" class="text-[0.78rem] tracking-[0.1em] uppercase text-white/40 hover:text-white/90 transition-colors">Beranda</a>
                    <a href="#tentang" class="text-[0.78rem] tracking-[0.1em] uppercase text-white/40 hover:text-white/90 transition-colors">Tentang</a>
                    <a href="#cekTransaksi" class="text-[0.78rem] tracking-[0.1em] uppercase text-white/40 hover:text-white/90 transition-colors">Cek Nota</a>
                </nav>
            </div>
            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-[0.75rem] text-white/25">
                    &copy; <span id="get-current-year"></span>
                    <span class="inline-block w-1 h-1 bg-[var(--gold)] rounded-full mx-2 align-middle"></span>
                    {{ config('app.name') }}. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        document.getElementById("get-current-year").innerHTML = new Date().getFullYear();

        // Nav scroll effect
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 60);
        });

        // Fade-up on scroll
        const faders = document.querySelectorAll('.fade-up');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('visible'), i * 100);
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        faders.forEach(el => observer.observe(el));
    </script>

    <script>
        $(document).ready(function () {
            // Allow Enter key
            $("input[name='nota']").on('keydown', function(e) {
                if (e.key === 'Enter') $("button[name='cekTansaksi']").click();
            });

            $("button[name='cekTansaksi']").click(function (e) {
                e.preventDefault();

                let notaInput = $("input[name='nota']").val().trim();
                if (!notaInput) return;

                $("#hasilCek").html(`
                    <div class="flex justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[var(--teal)]/20 border-t-[var(--teal)] rounded-full animate-spin"></div>
                    </div>
                `);

                $.ajax({
                    type: "get",
                    url: "{{ route('landing-page.nota') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "nota": notaInput
                    },
                    success: function(data) {
                        let formatter = new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 });
                        let totalBayar = formatter.format(data[0].total_bayar_akhir);

                        let date = new Date(data[0].tanggal);
                        let tanggal = new Intl.DateTimeFormat('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }).format(date);

                        let itemsHtml = data[1].map(item => `
                            <div class="flex justify-between items-start py-3 border-b border-[var(--ink)]/5 last:border-0">
                                <div>
                                    <div class="text-[0.9rem] font-medium text-[var(--ink)] mb-1">${item.cucian} <span class="text-[0.8rem] text-[var(--ink-muted)] font-normal">(${item.total} kg)</span></div>
                                    <div class="text-[0.75rem] text-[var(--teal)]">${item.layanan.join(' · ')}</div>
                                </div>
                            </div>
                        `).join('');

                        $("#hasilCek").html(`
                            <div class="mt-10 bg-white border border-[var(--ink)]/10 rounded-sm overflow-hidden animate-[slide-up_0.4s_ease]">
                                <div class="px-7 py-5 bg-[var(--ink)] flex justify-between items-center">
                                    <span class="text-[0.72rem] font-medium tracking-[0.18em] uppercase text-white/60">Detail Transaksi</span>
                                    <span class="text-[0.65rem] font-medium tracking-[0.18em] uppercase text-[var(--gold)] border border-[var(--gold)]/40 px-3 py-1 rounded-sm">${data[0].status}</span>
                                </div>
                                <div class="p-7">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-7">
                                        <div>
                                            <div class="text-[0.65rem] font-medium tracking-[0.18em] uppercase text-[var(--ink-muted)] mb-1">Nomor Nota</div>
                                            <div class="text-[0.95rem] text-[var(--ink)]">${data[0].nota_pelanggan}</div>
                                        </div>
                                        <div>
                                            <div class="text-[0.65rem] font-medium tracking-[0.18em] uppercase text-[var(--ink-muted)] mb-1">Tanggal</div>
                                            <div class="text-[0.95rem] text-[var(--ink)]">${tanggal}</div>
                                        </div>
                                        <div>
                                            <div class="text-[0.65rem] font-medium tracking-[0.18em] uppercase text-[var(--ink-muted)] mb-1">Pelanggan</div>
                                            <div class="text-[0.95rem] text-[var(--ink)]">${data[0].pelanggan_nama}</div>
                                        </div>
                                        <div>
                                            <div class="text-[0.65rem] font-medium tracking-[0.18em] uppercase text-[var(--ink-muted)] mb-1">Cabang</div>
                                            <div class="text-[0.95rem] text-[var(--ink)]">${data[0].cabang_nama}</div>
                                        </div>
                                    </div>
                                    <div class="h-[1px] bg-[var(--ink)]/5 mb-6"></div>
                                    <div class="text-[0.65rem] font-medium tracking-[0.18em] uppercase text-[var(--ink-muted)] mb-4">Detail Cucian</div>
                                    ${itemsHtml}
                                </div>
                                <div class="flex justify-between items-baseline px-7 py-5 bg-[#fafaf8] border-t border-[var(--ink)]/10">
                                    <div>
                                        <div class="text-[0.72rem] font-medium tracking-[0.15em] uppercase text-[var(--ink-muted)]">Total Pembayaran</div>
                                        <div class="text-[0.7rem] text-[var(--teal)] mt-1 tracking-[0.1em] uppercase">${data[0].jenis_pembayaran}</div>
                                    </div>
                                    <div class="font-serif text-3xl font-semibold text-[var(--ink)]">${totalBayar}</div>
                                </div>
                            </div>
                        `);
                    },
                    error: function() {
                        $("#hasilCek").html(`
                            <div class="mt-8 p-6 border border-[#b43c3c]/20 bg-[#b43c3c]/5 rounded-sm text-center text-[#7a3535] text-[0.85rem] animate-[slide-up_0.3s_ease]">
                                <i class="ri-information-line text-lg block mb-2"></i>
                                Transaksi dengan nomor nota tersebut tidak ditemukan. Pastikan nomor nota Anda benar.
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>
</html>