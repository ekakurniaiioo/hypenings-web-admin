<div class="bg-gray-800 text-white w-64 min-h-screen flex-shrink-0">
	<div class="p-4">
		<h1 class="text-2xl font-bold">Admin Panel</h1>
	</div>
	<nav class="mt-6">
		<div class="px-4 py-2 text-gray-400 uppercase text-xs font-semibold">Main</div>
		<a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('dashboard') ? 'text-white bg-gray-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
			</svg>
			<span class="mx-3">Dashboard</span>
		</a>

		<div class="px-4 py-2 mt-4 text-gray-400 uppercase text-xs font-semibold">Management</div>
		<a href="{{ route('news.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('news.*') ? 'text-white bg-gray-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
			</svg>
			<span class="mx-3">News</span>
		</a>
		<div class="px-4 py-2 mt-4 text-gray-400 uppercase text-xs font-semibold">Settings</div>
		<a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
			</svg>
			<span class="mx-3">Settings</span>
		</a>
	</nav>
</div>