<div class="bg-blue-800 text-white w-64 min-h-screen p-4">
    <div class="flex items-center mb-8">
        <img src="/img/icon_fab.png" alt="Logo" class="h-8 w-8 mr-2">
        <span class="font-semibold text-lg">FAB Retail</span>
    </div>

    @auth
        @php
            $role = trim(auth()->user()->role);
        @endphp

        @switch($role)
            @case('staff')
            @case('avp')
                <div class="mb-4 group">
                    <button class="flex items-center w-full text-left py-2 px-4 hover:bg-blue-700 rounded">
                        <span>Layanan CSS</span>
                        <svg class="w-4 h-4 ml-auto transform group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="pl-4 mt-2 hidden group-hover:block">
                        <a href="/kapasitas" class="block py-2 px-4 hover:bg-blue-700 rounded">Kapasitas Internet</a>
                        <a href="/perangkat" class="block py-2 px-4 hover:bg-blue-700 rounded">Perangkat</a>
                        <a href="/faq" class="block py-2 px-4 hover:bg-blue-700 rounded">FAQ</a>
                    </div>
                </div>
            @break

            @case('avp')
                <div class="mb-4">
                    <a href="/verifikasi" class="flex items-center w-full text-left py-2 px-4 hover:bg-blue-700 rounded">
                        <span>Verifikasi</span>
                    </a>
                </div>
            @break

            @case('gm_rcs')
                <div class="mb-4">
                    <a href="/pricing" class="flex items-center w-full text-left py-2 px-4 hover:bg-blue-700 rounded">
                        <span>Pricing CSS</span>
                    </a>
                </div>
            @break

            @default
                <p class="text-sm text-gray-300">Role tidak dikenali.</p>
        @endswitch
    @endauth
</div>
