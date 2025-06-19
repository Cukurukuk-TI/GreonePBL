<!-- Sidebar -->
<div class="w-60 bg-white border-r border-gray-300 p-4 pt-20">
    <!-- Profile Info -->
    <div class="flex flex-col items-center mb-8">
        @if (auth()->user()->foto)
            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile Photo"
                 class="w-24 h-24 rounded-full object-cover mb-4">
        @else
            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                <span class="text-3xl text-gray-500">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
        @endif

        <h3 class="font-medium text-lg">{{ auth()->user()->name }}</h3>
        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
    </div>

    <!-- Navigation -->
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('profile.content') }}"
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('profile.content') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>My Account</span>
                </a>
            </li>
            <li>
                <a href="{{ route('alamat.index') }}"
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('alamat.*') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    <span>Address</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.pesanan') }}"
                   class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('user.pesanan') ? 'bg-green-100 text-green-700 font-semibold' : 'text-gray-700 hover:bg-green-100' }}">
                    <i class="fas fa-shopping-bag mr-3"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <div onclick="showLogoutModal()"
                     class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-100 cursor-pointer">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Logout Modal -->
    <div id="logout-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div id="logout-modal-content"
             class="bg-white rounded-lg shadow-lg p-6 transform scale-95 opacity-0 transition-all duration-300 max-w-md w-full">
            <h2 class="text-xl font-semibold mb-4 text-center">Konfirmasi Logout</h2>
            <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="flex justify-center space-x-4">
                <button onclick="document.getElementById('logout-form').submit()"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Ya, Logout
                </button>
                <button onclick="hideLogoutModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
</div>

<!-- Script Modal -->
<script>
    function showLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const content = document.getElementById('logout-modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const content = document.getElementById('logout-modal-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
