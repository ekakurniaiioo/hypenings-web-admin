<header class="bg-white shadow-md border-b border-gray-200">
  <div class="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
    {{-- Left: Sidebar Toggle + Search --}}
    <div class="flex items-center space-x-4">
      {{-- Sidebar Toggle (mobile) --}}
      <button id="sidebarToggle" aria-label="Toggle sidebar"
        class="text-gray-600 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md lg:hidden transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      {{-- Search Bar --}}
      <form action="{{ route('articles.index') }}" method="GET" class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
        </span>
        <input type="text" name="search" placeholder="Search articles..." value="{{ request('search') }}" class="w-36 sm:w-64 md:w-80 pl-10 pr-4 py-2 rounded-md border border-gray-300 text-gray-700 placeholder-gray-400
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
      </form>
    </div>

    {{-- Right: Notification + User Menu --}}
    <div class="flex items-center space-x-6">
      @php
    use App\Models\Notification;
    $notifications = Notification::latest()->take(5)->get();
  @endphp
      {{-- Notification Bell --}}
      <div class="relative">
        <button id="notifToggle"
          class="p-2 rounded-full text-gray-500 hover:text-indigo-600 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition relative">
          <span class="sr-only">Notifications</span>
          <span
            class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse border border-white"></span>
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
        <a href="/login" class="block focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
          <img src="{{ asset('image/hype.png') }}" alt="User Avatar"
            class="w-9 h-9 rounded-full object-cover border border-gray-300 hover:ring-2 hover:ring-indigo-500 transition" />
        </a>
      </div>
    </div>
  </div>
</header>

