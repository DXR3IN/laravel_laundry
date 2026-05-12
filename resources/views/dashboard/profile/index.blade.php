@extends("dashboard.layouts.main")

@section("js")
    <script>
        // Konfigurasi SweetAlert modern
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
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                icon: 'error',
                ...swalConfig
            })
        @endif
    </script>
@endsection

@section("container")
    <div class="max-w-5xl mx-auto py-4">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Detail informasi akun untuk <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $user->email }}</span></p>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <a href="{{ route("profile.edit.password", $user->slug) }}" class="btn bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 w-full sm:w-auto shadow-sm">
                    <i class="ri-lock-password-line text-lg"></i> Ganti Password
                </a>
                <a href="{{ route("profile.edit", $user->slug) }}" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">
                    <i class="ri-pencil-fill text-lg"></i> Ubah Data
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 p-6 md:p-8 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                
                <div class="flex-shrink-0">
                    @if ($profile->foto)
                        <img src="{{ asset("storage/" . $profile->foto) }}" alt="{{ $user->username }}" class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-md" />
                    @else
                        <div class="w-32 h-32 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 text-4xl border-4 border-white dark:border-slate-800 shadow-inner">
                            <i class="ri-user-3-fill"></i>
                        </div>
                    @endif
                </div>

                <div class="text-center md:text-left pt-2">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $profile->nama }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">{{ $user->email }}</p>
                    <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 uppercase tracking-wider">
                        {{ $user->roles[0]->name }}
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-10">
                
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Informasi Akun</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Username</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $user->username }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">
                                {{ $user->roles[0]->name == 'rw' ? 'Nomor RW' : 'Cabang' }}
                            </span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                @if ($user->roles[0]->name == 'rw')
                                    {{ $profile->nomor_rw ?: '-' }}
                                @else
                                    {{ $user->cabang ? $user->cabang->nama : '-' }}
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Data Pribadi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Jenis Kelamin</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $profile->jenis_kelamin == 'L' ? 'Laki-laki' : ($profile->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Tempat Lahir</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $profile->tempat_lahir ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Lahir</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $profile->tanggal_lahir ?: '-' }}
                            </div>
                        </div>

                        <div class="md:col-span-2 lg:col-span-3">
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Telepon / WhatsApp</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $profile->telepon ?: '-' }}
                            </div>
                        </div>

                        <div class="md:col-span-2 lg:col-span-3">
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Alamat Lengkap</span>
                            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium min-h-[5rem] whitespace-pre-wrap">{{ $profile->alamat ?: '-' }}</div>
                        </div>

                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Data Ketenagakerjaan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Mulai Kerja</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $profile->mulai_kerja ?: '-' }}
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Selesai Kerja</span>
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                {{ $profile->selesai_kerja ?: '-' }}
                            </div>
                        </div>

                    </div>
                </div>

                @if ($user->roles[0]->name == "gamis")
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-widest border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Data Gamis</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div>
                                <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">Kartu Keluarga</span>
                                <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                    {{ $profile->gamis ? $profile->gamis->kartu_keluarga : "-" }}
                                </div>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">RT</span>
                                <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                    {{ $profile->gamis ? $profile->gamis->rt : "-" }}
                                </div>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1.5">RW</span>
                                <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 font-medium">
                                    {{ $profile->gamis ? $profile->gamis->rw : "-" }}
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection