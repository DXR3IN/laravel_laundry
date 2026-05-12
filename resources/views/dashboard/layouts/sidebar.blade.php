<div id="sidebarOverlay" class="fixed inset-0 z-[980] bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none xl:hidden"></div>

<aside class="dark:bg-slate-900 bg-white border-r border-slate-200 dark:border-slate-800 max-w-64 ease-nav-brand z-[990] fixed inset-y-0 !left-0 !ml-0 block w-full -translate-x-full flex-wrap items-center justify-between p-0 antialiased transition-transform duration-300 xl:translate-x-0" aria-expanded="false">
    
    <div class="h-20 flex items-center justify-between px-6">
        <a class="m-0 flex items-center whitespace-nowrap text-sm text-slate-700 dark:text-white" href="{{ route("dashboard") }}">
            <img src="{{ asset("img/logo-laundry-simokerto-noback.png") }}" class="ease-nav-brand h-9 max-w-full transition-all duration-200 dark:hidden" alt="main_logo" />
            <img src="{{ asset("img/logo-laundry-simokerto-noback.png") }}" class="ease-nav-brand hidden h-9 max-w-full transition-all duration-200 dark:block" alt="main_logo" />
            <span class="ease-nav-brand ml-3 font-bold tracking-wide transition-all duration-200">Top Laundry</span>
        </a>
        <i class="ri-close-large-fill cursor-pointer text-xl text-slate-400 hover:text-red-500 transition-colors dark:text-slate-300 xl:hidden" sidenav-close></i>
    </div>

    <div class="mx-6 h-px bg-slate-200 dark:bg-slate-700/50 border-0"></div>

    <div class="h-sidenav block max-h-[calc(100vh-80px)] w-auto grow basis-full items-center overflow-y-auto overflow-x-hidden pb-8 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        <ul class="mb-0 flex flex-col pl-0 mt-4">
            
            <li class="w-full">
                <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs("dashboard") ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("dashboard") }}">
                    <i class="ri-tv-2-line text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs("dashboard") ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                    <span class="ml-3 pointer-events-none">Dashboard</span>
                </a>
            </li>

            @role('owner')
                <li class="mt-6 w-full">
                    <h6 class="ml-8 text-[11px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-500">Data Master</h6>
                </li>

                <li class="mt-2 w-full">
                    <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["cabang"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("cabang") }}">
                        <i class="ri-home-smile-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["cabang"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">Cabang</span>
                    </a>
                </li>

                <li class="mt-1 w-full">
                    <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["umr"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("umr") }}">
                        <i class="ri-currency-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["umr"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">UMR</span>
                    </a>
                </li>
            @endrole

            @role(["manajer_laundry", "owner"])
                {{-- Awal User Management --}}
                <li class="mt-6 w-full">
                    <h6 class="ml-8 text-[11px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-500">User Management</h6>
                </li>

                <li class="mt-2 w-full">
                    <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["user", "user.cabang", "user.cabang.create", "user.create", "user.view", "user.edit", "user.edit.password", "user.trash"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("user") }}">
                        <i class="ri-user-3-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["user", "user.cabang", "user.cabang.create", "user.create", "user.view", "user.edit", "user.edit.password", "user.trash"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">Akun</span>
                    </a>
                </li>

                @role('owner')
                    <li class="mt-2 w-full">
                    <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["owner", "owner.cabang", "owner.cabang.create", "owner.create", "owner.view", "owner.edit", "owner.edit.password"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("owner") }}">
                        <i class="ri-user-star-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["owner", "owner.cabang", "owner.cabang.create", "owner.create", "owner.view", "owner.edit", "owner.edit.password", "owner.trash"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">Owner</span>
                    </a>
                </li>
                @endrole
                {{-- Akhir User Management --}}

                {{-- Awal Layanan --}}
                <li class="mt-6 w-full">
                    <h6 class="ml-8 text-[11px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-500">Layanan</h6>
                </li>

                 @role('owner')
                    <li class="mt-2 w-full">
                        <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["layanan-cabang", "layanan-cabang.cabang", "layanan-cabang.trash"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("layanan-cabang") }}">
                            <i class="ri-service-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["layanan-cabang"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Layanan Cabang</span>
                        </a>
                    </li>
                @endrole
                
                @role('manajer_laundry')
                    <li class="mt-2 w-full">
                        <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["jenis-layanan"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("jenis-layanan") }}">
                            <i class="ri-hand-heart-line text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["jenis-layanan"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Jenis Layanan</span>
                        </a>
                    </li>

                    <li class="mt-1 w-full">
                        <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["jenis-cucian"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("jenis-cucian") }}">
                            <i class="ri-shirt-line text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["jenis-cucian"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Jenis Cucian</span>
                        </a>
                    </li>
                    
                    <li class="mt-1 w-full">
                        <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["layanan-prioritas"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("layanan-prioritas") }}">
                            <i class="ri-customer-service-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["layanan-prioritas"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Layanan Prioritas</span>
                        </a>
                    </li>

                    <li class="mt-1 w-full">
                        <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["harga-jenis-layanan"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("harga-jenis-layanan") }}">
                            <i class="ri-price-tag-3-line text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["harga-jenis-layanan"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Harga Jenis Layanan</span>
                        </a>
                    </li>

                    <li class="mt-1 w-full">
                        <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["layanan-tambahan"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("layanan-tambahan") }}">
                            <i class="ri-hand-heart-line text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["layanan-tambahan"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Layanan Tambahan</span>
                        </a>
                    </li>
                @endrole
                {{-- Akhir Layanan --}}
            @endrole

            @role(["manajer_laundry", "pegawai_laundry", 'owner'])
                {{-- Awal Transaksi --}}
                <li class="mt-6 w-full">
                    <h6 class="ml-4 text-[11px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-500">Transaksi</h6>
                </li>

                <li class="mt-2 w-full px-4">
                    <a class="group ease-nav-brand flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["pelanggan", "pelanggan.cabang"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("pelanggan") }}">
                        <i class="ri-user-5-line text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["pelanggan", "pelanggan.cabang"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">Pelanggan</span>
                    </a>
                </li>

                @role(["owner"])
                    <li class="mt-1 w-full px-4">
                        <a class="group ease-nav-brand flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["transaksi.owner", "transaksi.owner.cabang", "transaksi.owner.cabang.jadwal", "transaksi.owner.view", "transaksi.owner.view.layanan", "transaksi.owner.cabang.create", "transaksi.owner.cabang.edit"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" href="{{ route("transaksi.owner") }}">
                            <i class="ri-shopping-bag-4-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["transaksi.owner", "transaksi.owner.cabang", "transaksi.owner.cabang.jadwal", "transaksi.owner.view", "transaksi.owner.view.layanan", "transaksi.owner.cabang.create", "transaksi.owner.cabang.edit"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Transaksi Cabang</span>
                        </a>
                    </li>
                @endrole
                
                @role(['manajer_laundry', 'pegawai_laundry'])
                    <li class="mt-1 w-full px-4">
                        <a class="group ease-nav-brand flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["transaksi", "transaksi.view", "transaksi.view.layanan", "transaksi.create", "transaksi.edit"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("transaksi") }}">
                            <i class="ri-todo-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["transaksi", "transaksi.view", "transaksi.view.layanan", "transaksi.create", "transaksi.edit"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Transaksi Layanan</span>
                        </a>
                    </li>
                
                    <li class="mt-1 w-full px-4">
                        <a class="group ease-nav-brand flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["transaksi.jadwal"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("transaksi.jadwal") }}">
                            <i class="ri-timeline-view text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["transaksi.jadwal"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                            <span class="ml-3 pointer-events-none">Jadwal Layanan</span>
                        </a>
                    </li>
                @endrole
                {{-- Akhir Transaksi --}}
            @endrole

            @role(["manajer_laundry", "owner"])
                {{-- Awal Laporan --}}
                <li class="mt-6 w-full">
                    <h6 class="ml-8 text-[11px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-500">Laporan</h6>
                </li>

                <li class="mt-2 w-full">
                    <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["laporan.pendapatan.laundry"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("laporan.pendapatan.laundry") }}">
                        <i class="ri-book-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["laporan.pendapatan.laundry"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">Pendapatan Laundry</span>
                    </a>
                </li>
                <li class="mt-1 w-full">
                    <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["laporan.pelanggan"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("laporan.pelanggan") }}">
                        <i class="ri-book-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["laporan.pelanggan"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                        <span class="ml-3 pointer-events-none">Pelanggan</span>
                    </a>
                </li>
                {{-- Akhir Laporan --}}
            @endrole

            <li class="mt-6 w-full">
                <h6 class="ml-8 text-[11px] font-bold tracking-wider uppercase text-slate-400 dark:text-slate-500">Pengaturan</h6>
            </li>

            <li class="mt-2 w-full">
                <a class="group ease-nav-brand mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-300 {{ Request::routeIs(["profile", "profile.edit", "profile.edit.password"]) ? "bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 shadow-sm" : "text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white" }}" wire:navigate href="{{ route("profile", auth()->user()->slug) }}">
                    <i class="ri-profile-fill text-lg transition-transform duration-300 group-hover:scale-110 {{ Request::routeIs(["profile", "profile.edit", "profile.edit.password"]) ? "text-blue-600 dark:text-blue-400" : "text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" }}"></i>
                    <span class="ml-3 pointer-events-none">Profile</span>
                </a>
            </li>

            <li class="mt-1 w-full">
                <form method="POST" action="{{ route("logout") }}" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="group ease-nav-brand w-full mx-4 flex items-center rounded-xl px-4 py-3 text-sm font-medium text-slate-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 transition-all duration-300 text-left cursor-pointer">
                        <i class="ri-login-box-line text-lg text-slate-400 transition-transform duration-300 group-hover:scale-110 group-hover:text-red-600 dark:group-hover:text-red-400"></i>
                        <span class="ml-3 pointer-events-none">Logout</span>
                    </button>
                </form>
            </li>
            
        </ul>
    </div>
</aside>