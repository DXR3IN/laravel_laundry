@extends("dashboard.layouts.main")

@section("js")
    <script>
        // Script Preview Image
        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('#img-preview');

            if (image.files && image.files[0]) {
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }
        }

        // Konfigurasi SweetAlert Modern (Blue Theme)
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
                text: 'Gagal memperbarui profil. Silakan periksa kembali form Anda.',
                icon: 'error',
                ...swalConfig
            })
        @endif
    </script>
@endsection

@section("container")
    <div class="max-w-5xl mx-auto py-4">
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $title }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Perbarui foto, informasi akun, dan data diri Anda di bawah ini.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <form action="{{ route("profile.update", $user->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 md:p-8 space-y-10">
                    
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Foto Profil</h3>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 rounded-full ring-4 ring-slate-100 dark:ring-slate-800 overflow-hidden bg-slate-200 dark:bg-slate-700">
                                    @if ($profile->foto)
                                        <img id="img-preview" src="{{ asset("storage/" . $profile->foto) }}" alt="{{ $user->username }}" class="w-full h-full object-cover" />
                                    @elseif ($profile->jenis_kelamin == "L")
                                        <img id="img-preview" src="{{ asset("img/team-2.jpg") }}" class="w-full h-full object-cover" />
                                    @elseif ($profile->jenis_kelamin == "P")
                                        <img id="img-preview" src="{{ asset("img/team-1.jpg") }}" class="w-full h-full object-cover" />
                                    @else
                                        <img id="img-preview" src="{{ asset("img/home-decor-1.jpg") }}" class="w-full h-full object-cover" />
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex-grow w-full">
                                <input type="file" name="foto" id="foto" onchange="previewImage()" accept="image/*"
                                    class="file-input file-input-bordered w-full max-w-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" />
                                <p class="text-xs text-slate-500 mt-2 dark:text-slate-400">Format yang didukung: JPG, PNG. Disarankan rasio 1:1.</p>
                                @error("foto") <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Informasi Akun</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Username</span></label>
                                <input type="text" name="username" placeholder="Username" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $user->username }}" required />
                                @error("username") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Email</span></label>
                                <input type="email" name="email" placeholder="Email" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $user->email }}" required />
                                @error("email") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Data Diri (Profil)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Nama Lengkap</span></label>
                                <input type="text" name="nama" placeholder="Nama Lengkap" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $profile->nama }}" required />
                                @error("nama") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Jenis Kelamin</span></label>
                                <div class="flex gap-4 items-center h-12">
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" value="L" name="jenis_kelamin" class="radio radio-primary radio-sm dark:border-slate-500" @if ($profile->jenis_kelamin == "L") checked @endif required />
                                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Laki-laki</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center gap-2">
                                        <input type="radio" value="P" name="jenis_kelamin" class="radio radio-primary radio-sm dark:border-slate-500" @if ($profile->jenis_kelamin == "P") checked @endif required />
                                        <span class="label-text text-slate-700 dark:text-slate-300 font-medium">Perempuan</span>
                                    </label>
                                </div>
                                @error("jenis_kelamin") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Tempat Lahir</span></label>
                                <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $profile->tempat_lahir }}" required />
                                @error("tempat_lahir") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Tanggal Lahir</span></label>
                                <input type="date" name="tanggal_lahir" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white [color-scheme:light] dark:[color-scheme:dark]" value="{{ $profile->tanggal_lahir }}" required />
                                @error("tanggal_lahir") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Telepon / WhatsApp</span></label>
                                <input type="text" name="telepon" placeholder="Nomor Telepon" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $profile->telepon }}" required />
                                @error("telepon") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Alamat Lengkap</span></label>
                                <textarea name="alamat" placeholder="Tuliskan alamat lengkap..." rows="3" class="textarea textarea-bordered w-full text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>{{ $profile->alamat }}</textarea>
                                @error("alamat") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3 rounded-b-2xl">
                    <a href="{{ url()->previous() }}" class="btn btn-ghost border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 w-full sm:w-auto">Batal & Kembali</a>
                    <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 w-full sm:w-auto shadow-sm">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>
@endsection