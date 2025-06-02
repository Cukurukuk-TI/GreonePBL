<div class="p-4">
    <!-- Profile Info -->
    <div class="flex flex-col items-center mb-8">
        @if(auth()->user()->foto)
            <img src="{{ asset('storage/'.auth()->user()->foto) }}" 
                 alt="Profile Photo" 
                 class="w-24 h-24 rounded-full object-cover mb-4">
        @else
            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                <span class="text-3xl text-gray-500">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
        @endif
        
        <h3 class="font-medium text-lg">{{ auth()->user()->name }}</h3>
        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
    </div>

    <!-- Navigation -->
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-gray-100 font-medium' : '' }}">
                    <i class="fas fa-user-circle mr-3"></i>
                    <span>My Account</span>
                </a>
            </li>
            <li>
                <a href="#" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-id-card mr-3"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('alamat.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    <span>Address</span>
                </a>
            </li>
            <li>
                <a href="#" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-shopping-bag mr-3"></i>
                    <span>Orders</span>
                </a>
            </li>
        </ul>
    </nav>
</div>