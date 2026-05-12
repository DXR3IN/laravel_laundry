@extends("dashboard.layouts.main")

@section("js")
    <script>
        // Mengubah warna tombol SweetAlert agar selaras dengan tema biru modern (Blue-600)
        const swalConfig = {
            confirmButtonColor: '#2563EB',
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
                text: '{{ $title }} Gagal Diperbarui. Silakan periksa kembali form Anda.',
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
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Perbarui informasi data akun dan profil pengguna di bawah ini.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <form action="{{ route("user.update", $user->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 md:p-8 space-y-10">
                    
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Informasi Akun</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Role'" /></span></label>
                                <select name="role" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    <option disabled selected>Pilih Role!</option>
                                    @foreach ($role as $item)
                                        <option value="{{ $item->name }}" @if ($item->name == $user->roles[0]->name) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error("role") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Cabang'" /></span></label>
                                <select name="cabang_id" class="select select-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>
                                    <option disabled selected>Pilih Cabang!</option>
                                    @foreach ($cabang as $item)
                                        <option value="{{ $item->id }}" @if ($item->id == auth()->user()->cabang_id || $item->id == $user->cabang_id) selected @endif>{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                @error("cabang_id") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Username'" /></span></label>
                                <input type="text" name="username" placeholder="johndoe123" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $user->username }}" required />
                                @error("username") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Email'" /></span></label>
                                <input type="email" name="email" placeholder="john@example.com" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $user->email }}" required />
                                @error("email") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Data Diri (Profil)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Nama Lengkap'" /></span></label>
                                <input type="text" name="nama" placeholder="Nama Lengkap" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $profile->nama }}" required />
                                @error("nama") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Jenis Kelamin'" /></span></label>
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
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Tempat Lahir'" /></span></label>
                                <input type="text" name="tempat_lahir" placeholder="Surabaya" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $profile->tempat_lahir }}" required />
                                @error("tempat_lahir") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Tanggal Lahir'" /></span></label>
                                <input type="date" name="tanggal_lahir" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white [color-scheme:light] dark:[color-scheme:dark]" value="{{ $profile->tanggal_lahir }}" required />
                                @error("tanggal_lahir") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Telepon / WhatsApp'" /></span></label>
                                <input type="text" name="telepon" placeholder="081234567890" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" value="{{ $profile->telepon }}" required />
                                @error("telepon") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full md:col-span-2">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300"><x-label-input-required :value="'Alamat Lengkap'" /></span></label>
                                <textarea name="alamat" placeholder="Tuliskan alamat lengkap..." rows="3" class="textarea textarea-bordered w-full text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white" required>{{ $profile->alamat }}</textarea>
                                @error("alamat") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-200 dark:border-slate-700 pb-2 mb-6">Data Ketenagakerjaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Mulai Kerja</span></label>
                                <input type="date" name="mulai_kerja" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white [color-scheme:light] dark:[color-scheme:dark]" value="{{ $profile->mulai_kerja }}" />
                                @error("mulai_kerja") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label pt-0"><span class="label-text font-medium text-slate-700 dark:text-slate-300">Selesai Kerja</span></label>
                                <input type="date" name="selesai_kerja" class="input input-bordered w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white [color-scheme:light] dark:[color-scheme:dark]" value="{{ $profile->selesai_kerja }}" />
                                @error("selesai_kerja") <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
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