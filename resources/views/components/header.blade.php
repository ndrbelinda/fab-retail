<div class="flex justify-end mb-8">
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900">
            @auth
                <span class="mr-2">{{ auth()->user()->username }}</span>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">
                    {{ auth()->user()->role }}
                </span>
                @endauth
            
            <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
            </form>
        </div>
    </div>
</div>
