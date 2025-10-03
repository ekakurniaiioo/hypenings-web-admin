<header class="bg-gray-50 rounded-b-3xl shadow-md border-b border-gray-400">
  <div class="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
    
    <div class="flex items-center space-x-4">
      {{-- Search Bar --}}
      <form action="{{ route('articles.index') }}" method="GET" class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
        </span>
        <input type="text" name="search" placeholder="Search articles..." value="{{ request('search') }}" class="w-36 sm:w-64 md:w-80 pl-10 pr-4 py-2 rounded-2xl border border-gray-300 text-gray-700 placeholder-gray-400
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
      </form>
    </div>

    {{-- Right: Notification + User Menu --}}
    <div class="flex items-center space-x-6">

      @php
      use App\Models\Notification;

      $notifications = Notification::latest()->take(8)->get();
      $hasUnread = Notification::where('is_read', false)->exists();
      @endphp

      {{-- Notification Bell --}}
      <div class="relative">
        <button id="notifToggle" class="relative p-2 rounded-full text-gray-500 
         hover:text-indigo-600 hover:bg-indigo-100 
         focus:outline-none focus:ring-2 focus:ring-indigo-400 
         transition-all duration-200 ease-out 
         hover:scale-110 active:scale-95">
          <span class="sr-only">Notifications</span>

          @if($hasUnread)
        <span
        class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse border border-white"></span>
      @endif

          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <path
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>

        <!-- Dropdown -->
        <div id="notifDropdown"
          class="hidden absolute right-0 mt-3 w-72 bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50
                 max-h-80 overflow-auto scrollbar-thin scrollbar-thumb-indigo-400 scrollbar-track-gray-100 transition ease-in-out duration-200">
          <div class="p-4">
            <h4 class="text-gray-600 font-semibold text-sm mb-3 border-b border-gray-200 pb-2">Notifikasi Terbaru</h4>
            <ul>
              @forelse ($notifications as $notif)
          <li class="text-gray-700 text-sm mb-3 last:mb-0 border-b border-gray-100 pb-2">
          <strong class="block mb-1">{{ $notif->title }}</strong>
          <p class="text-gray-500 text-xs">{{ $notif->message }}</p>
          </li>
        @empty
          <li class="text-gray-400 text-sm">Belum ada notifikasi.</li>
        @endforelse
            </ul>
          </div>
        </div>
      </div>

      {{-- User Menu (Avatar) --}}
      <div>
        <button id="openDrawer" class="block focus:outline-none rounded-full">
          <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('image/user.jpg') }}"
            alt="avatar"
            class="w-10 h-10 rounded-full object-cover border border-gray-300 hover:ring-2 hover:ring-indigo-500 transition" />
        </button>
      </div>

      <!-- Overlay -->
      <div id="drawerOverlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"></div>

      <!-- Drawer -->
      <div id="drawer"
        class="fixed top-0 right-0 w-80 h-full bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 rounded-l-2xl">

        <div class="flex flex-col h-full">
          <!-- Header -->
          <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-gray-800">Profile</h2>
            <button id="closeDrawer"
              class="text-gray-400 hover:text-gray-700 transition text-2xl font-light">&times;</button>
          </div>

          <!-- Body -->
          <div class="flex-1 overflow-y-auto p-6">
            <!-- Foto Profil -->
            <div class="flex flex-col items-center mb-6">
              <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('image/user.jpg') }}"
                alt="Avatar" class="w-28 h-28 rounded-full border-4 border-gray-200 shadow-md object-cover mb-3">

              <!-- Tombol ubah foto -->
              <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label
                  class="cursor-pointer px-4 py-1.5 bg-blue-500 text-white text-sm rounded-lg shadow hover:bg-blue-600 transition">
                  Change Photo
                  <input type="file" name="avatar" class="hidden" onchange="this.form.submit()">
                </label>
              </form>
            </div>

            <!-- Nama & Email -->
            <div class="space-y-3 text-gray-700 mt-12 w-full">
              <div>
                <p class="text-sm font-medium text-gray-500">Nama</p>
                <p class="text-base border-b font-semibold">{{ Auth::user()->name }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Role</p>
                <p class="text-base border-b font-semibold">{{ Auth::user()->role }}</p>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-base border-b font-semibold">{{ Auth::user()->email }}</p>
              </div>
            </div>
          </div>

          <div class="px-6 py-4 border-t flex justify-end">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>