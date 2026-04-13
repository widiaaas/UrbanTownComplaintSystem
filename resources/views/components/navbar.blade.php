<nav
    class="bg-white border-b border-gray-200 sticky top-0 z-40
           transition-all duration-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- LEFT: HAMBURGER --}}
            <div class="flex items-center">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md hover:bg-gray-100"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 text-gray-700"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- RIGHT: USER INFO --}}
            <div class="flex items-center space-x-4">

                @php
                    $user = auth()->user();
                    $karyawan = $user?->karyawan;

                    $nama = $karyawan?->nama ?? 'User';
                    
                    // mapping role
                    if($karyawan){
                        if($karyawan->role === 'admin'){
                            $roleLabel = 'Admin';
                        } elseif($karyawan->role === 'tenant_relation'){
                            $roleLabel = 'Tenant Relation';
                        } else {
                            $roleLabel = $karyawan->departemen ?? '-';
                        }
                    } else {
                        $roleLabel = 'Unit';
                    }
                @endphp

                {{-- USER NAME --}}
                <div class="hidden sm:flex items-center space-x-3 border-l pl-4">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-gray-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A7 7 0 0112 14
                                 a7 7 0 016.879 3.804
                                 M15 11a3 3 0 11-6 0
                                 3 3 0 016 0z"/>
                    </svg>

                    <div class="text-right leading-tight">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $nama }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $roleLabel }}
                        </p>
                    </div>
                </div>

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center space-x-2 px-3 py-2
                               rounded-md text-sm font-medium
                               text-gray-700 hover:bg-gray-100 transition">
                        
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7
                                     m6 4v1a2 2 0 01-2 2H5
                                     a2 2 0 01-2-2V7
                                     a2 2 0 012-2h6
                                     a2 2 0 012 2v1"/>
                        </svg>

                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>