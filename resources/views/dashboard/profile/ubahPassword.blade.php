@extends("dashboard.layouts.main")

@section("js")
    <script>
        // Konfigurasi SweetAlert agar seragam dan modern
        const swalConfig = {
            confirmButtonColor: '#2563EB', // Blue-600
            confirmButtonText: 'OK',
            customClass: {
                popup: 'dark:bg-slate-800 dark:text-white',
                title: 'dark:text-white',
                htmlContainer: 'dark:text-slate-300'
            }
        };

        @if (session()->has("success"))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                ...swalConfig
            });
        @endif

        @if (session()->has("error"))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
                icon: 'error',
                ...swalConfig
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: 'Gagal memperbarui password. Silakan periksa kembali input Anda.',
                icon: 'error',
                ...swalConfig
            })
        @endif
    </script>
@endsection

@section("container")
    <div class="max-w-3xl mx-auto py-4">
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Kelola keamanan akun untuk <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $user->email }}</span>
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <form action="{{ route("profile.update.password", $user->slug) }}" method="POST">
                @csrf
                <input type="text" name="slug" value="{{ $user->slug }}" hidden>

                <div class="p-6 md:p-8 space-y-6">
                    
                    <div class="form-control w-full">
                        <label class="label pt-0 pb-1">
                            <span class="label-text font-medium text-slate-700 dark:text-slate-300">Password Lama</span>
                        </label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini" 
                            class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white transition-shadow" required />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <div class="divider my-2 opacity-50 dark:border-slate-700"></div>

                    <div class="form-control w-full">
                        <label class="label pt-0 pb-1">
                            <span class="label-text font-medium text-slate-700 dark:text-slate-300">Password Baru</span>
                        </label>
                        <input type="password" name="password" placeholder="Masukkan password baru" 
                            class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white transition-shadow" required />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label pt-0 pb-1">
                            <span class="label-text font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password Baru</span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru" 
                            class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white transition-shadow" required />
                    </div>

                    <div id="form_gamis"></div>

                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3 rounded-b-2xl">
                    <a href="{{ url()->previous() }}" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto">
                        Batal & Kembali
                    </a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection