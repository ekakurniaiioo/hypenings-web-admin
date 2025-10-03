<div class="bg-gray-900 text-gray-300 w-64 min-h-screen flex-shrink-0 flex flex-col">
	<!-- Header -->
	<div class="p-[36px] border-b border-gray-700">
		<h1 class="text-3xl font-extrabold text-white text-center tracking-wide">Admin Panel</h1>
	</div>

	<!-- Navigation -->
	<nav class="flex-1 overflow-y-auto mt-6">
		<!-- Main -->
		<div class="px-6 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">Main</div>
		<a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
           {{ request()->routeIs('dashboard')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<!-- Dashboard Icon -->
			<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
				stroke-linejoin="round" viewBox="0 0 24 24">
				<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 
                         0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
			</svg>
			<span class="ml-4">Dashboard</span>
		</a>

		<!-- Management -->
		<div class="px-6 mt-8 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">Management</div>

		<!-- Users -->
		<a href="{{ route('users.index') }}" class="group flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
   {{ request()->routeIs('users.*')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<!-- Users Icon -->
			<svg class="w-5 h-5 flex-shrink-0 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
				<path d="M17 21v-2a4 4 0 00-3-3.87M7 21v-2a4 4 0 013-3.87M12 7a4 4 0 110-8 4 4 0 010 8z" />
			</svg>
			<span class="ml-4 group-hover:text-white">Users</span>
		</a>

		<!-- News -->
		<a href="{{ route('articles.index') }}" class="group flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
   {{ request()->routeIs('articles.*')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<!-- News Icon (File/Document) -->
			<svg class="w-5 h-5 flex-shrink-0 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
				<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
				<polyline points="14 2 14 8 20 8" />
			</svg>
			<span class="ml-4 group-hover:text-white">News</span>
		</a>

		<!-- Categories -->
		<a href="{{ route('categories.index') }}" class="group flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
   {{ request()->routeIs('categories.*')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<!-- Categories Icon (Folder) -->
			<svg class="w-5 h-5 flex-shrink-0 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
				<path d="M3 7h4l2-2h10a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
			</svg>
			<span class="ml-4 group-hover:text-white">Categories</span>
		</a>

		<!-- Article Flag -->
		<a href="" class="group flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
   {{ request()->routeIs('flags.*')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<!-- Flag Icon -->
			<svg class="w-5 h-5 flex-shrink-0 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
				<path d="M4 4v16" />
				<path d="M4 4h12l-2 4 2 4H4" />
			</svg>
			<span class="ml-4 group-hover:text-white">Article Flag</span>
		</a>

		<!-- Settings -->
		<div class="px-6 mt-8 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">Settings</div>
		<a href="{{ route('settings.index') }}" class="flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
   {{ request()->routeIs('settings.*')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<!-- Settings Icon (Gear) -->
			<svg class="w-5 h-5 flex-shrink-0 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
				<circle cx="12" cy="12" r="3" />
				<path
					d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06c.46-.46.61-1.15.33-1.82a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06c.46.46 1.15.61 1.82.33.67-.28 1-1 1-1.51V3a2 2 0 014 0v.09c0 .51.33 1.23 1 1.51.67.28 1.36.13 1.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06c-.28.46-.43 1.15-.33 1.82.28.67 1 1 1.51 1H21a2 2 0 010 4h-.09c-.51 0-1.23.33-1.51 1z" />
			</svg>
			<span class="ml-4">Settings</span>
		</a>
	</nav>
</div>