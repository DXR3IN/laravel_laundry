<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login |Top Laundry</title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo-laundry-simokerto.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo-laundry-simokerto.png') }}" />

    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet" />
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>

<body class="antialiased bg-white text-slate-600 font-sans m-0">
    <main class="min-h-screen flex">
        
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <div class="mb-8">
                    <img src="{{ asset('img/logo-laundry-simokerto.png') }}" alt="Logo" class="h-12 w-auto mb-6">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Masuk ke akun Anda</h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Masukkan email dan password untuk melanjutkan
                    </p>
                </div>

                @if ($errors->get('email') || $errors->get('password'))
                    <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="font-medium">
                            {{ $errors->get('email')[0] ?? 'Email atau password yang Anda masukkan salah.' }}
                        </div>
                    </div>
                @endif

                <div class="mt-8">
                    <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" autofocus
                                    class="block w-full appearance-none rounded-lg border border-slate-300 px-3 py-2.5 placeholder-slate-400 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-blue-600 sm:text-sm transition-colors" 
                                    placeholder="nama@email.com">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="block w-full appearance-none rounded-lg border border-slate-300 px-3 py-2.5 placeholder-slate-400 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-blue-600 sm:text-sm transition-colors" 
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center">
                                <input id="rememberMe" name="remember" type="checkbox" 
                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                                <label for="rememberMe" class="ml-2 block text-sm text-slate-700 cursor-pointer select-none">
                                    Ingat saya
                                </label>
                            </div>

                            {{-- Uncomment jika rute Lupa Password sudah aktif --}}
                            {{-- 
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                    Lupa password?
                                </a>
                            </div> 
                            --}}
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                class="flex w-full justify-center rounded-lg border border-transparent bg-blue-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all">
                                Masuk ke Sistem
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover" 
                src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg" 
                alt="Laundry Image">
            
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent mix-blend-multiply"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-12 text-white">
                <blockquote class="max-w-xl">
                    <h3 class="text-3xl font-bold tracking-tight mb-4 text-white">
                        "Cuci bersih, hidup lebih rapi"
                    </h3>
                    <p class="text-lg text-slate-200 leading-relaxed font-light">
                        Cucian yang bersih mencerminkan semangat yang positif. Mulailah harimu dengan keharuman dan kerapihan bersama layanan kami.
                    </p>
                </blockquote>
            </div>
        </div>
        
    </main>
</body>

</html>