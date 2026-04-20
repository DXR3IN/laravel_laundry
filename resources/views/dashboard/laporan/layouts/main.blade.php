<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $judul }}</title>

    {{-- CARA BENAR MEMANGGIL CSS LOKAL UNTUK PDF --}}
    {{-- Ini akan menyisipkan isi css langsung ke dalam file tanpa harus request via URL (mencegah deadlock) --}}
    <style>
        <?php include public_path('css/pdf.css'); ?>
        
        /* Tambahan style dasar jika pdf.css kamu belum lengkap */
        body { font-family: sans-serif; font-size: 12px; }
        .clearfix::after { content: ""; display: table; clear: both; }
        header { margin-bottom: 20px; text-align: center; }
        #logo { margin-bottom: 10px; }
        #company { text-align: right; margin-bottom: 20px; font-size: 11px; }
        footer { width: 100%; text-align: center; position: fixed; bottom: 0; font-size: 10px; padding-top: 10px; border-top: 1px solid #ccc; }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            {{-- CARA BENAR MEMANGGIL GAMBAR LOKAL UNTUK PDF (GUNAKAN PUBLIC_PATH, BUKAN ASSET) --}}
            <img src="{{ public_path('img/logo-laundry-simokerto.png') }}" alt="logo" width="120">
        </div>
        
        <h1>{{ $judulTabel }}</h1>
        
        <div id="company" class="clearfix">
            <div>Program Laundry</div>
            <div>Simokerto,<br /> Surabaya, Jawa Timur</div>
            <div>{{ \Carbon\Carbon::now()->format("d F Y") }}</div>
        </div>
    </header>

    <main>
        @yield("tanggal")
        @yield("tabel")
    </main>

    <footer>
        {{ $footer }}
    </footer>
</body>
</html>