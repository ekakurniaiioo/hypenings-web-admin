<div class="bg-gray-900 text-gray-300 w-64 min-h-screen flex-shrink-0 flex flex-col">
	<div class="p-6 border-b border-gray-700">
		<h1 class="text-3xl font-extrabold text-white tracking-wide">Admin Panel</h1>
	</div>

	<nav class="flex-1 overflow-y-auto mt-6">
		<div class="px-6 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">Main</div>
		<a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
      {{ request()->routeIs('dashboard')
	? 'bg-blue-600 text-white border-l-4 border-blue-400 font-semibold hover:bg-blue-700 hover:text-white'
	: 'hover:bg-gray-800 hover:text-white' }}">
			<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
				stroke-linejoin="round" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
			</svg>
			<span class="ml-4">Dashboard</span>
		</a>

		<div class="px-6 mt-8 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">Management</div>
		<a href="{{ route('articles.index') }}" class="group flex items-center px-6 py-3 mb-1 rounded-r-md transition-colors duration-200
    {{ request()->routeIs('news.*')
	? 'bg-blue-600 hover:bg-blue-700 text-white border-l-4 border-blue-400 font-semibold'
	: 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
			<svg class="w-5 h-5 flex-shrink-0 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
			</svg>
			<span class="ml-4 group-hover:text-white">News</span>
		</a>

		<div class="px-6 mt-8 mb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider">Settings</div>
		<a href="{{ route('settings.index') }}"
			class="flex items-center px-6 py-3 mb-1 rounded-r-md hover:bg-gray-800 hover:text-white transition-colors duration-200">
			<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
				stroke-linejoin="round" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
				<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
			</svg>
			<span class="ml-4">Settings</span>
		</a>
	</nav>
</div>