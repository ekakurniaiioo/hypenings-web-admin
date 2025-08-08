@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-md shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Settings</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="border-b border-gray-300 mb-6">
        <nav class="flex space-x-6" aria-label="Tabs">
            <a href="#profile" class="tab-link px-3 py-2 font-medium text-indigo-600 border-b-2 border-indigo-600" data-tab="profile">Profile</a>
            <a href="#security" class="tab-link px-3 py-2 font-medium text-gray-600 hover:text-indigo-600" data-tab="security">Security</a>
            <a href="#appearance" class="tab-link px-3 py-2 font-medium text-gray-600 hover:text-indigo-600" data-tab="appearance">Appearance</a>
        </nav>
    </div>

    {{-- Profile Settings --}}
    <div id="profile" class="tab-content">
        <form method="POST" action="{{ route('settings.profile.update') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" />
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror" />
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Save Profile</button>
        </form>
    </div>

    {{-- Security Settings --}}
    <div id="security" class="tab-content hidden">
        <form method="POST" action="{{ route('settings.security.update') }}">
            @csrf
            <div class="mb-4">
                <label for="current_password" class="block text-gray-700 font-medium mb-1">Current Password</label>
                <input type="password" name="current_password" id="current_password"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('current_password') border-red-500 @enderror" />
                @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-1">New Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror" />
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Change Password</button>
        </form>
    </div>

    {{-- Appearance Settings --}}
    <div id="appearance" class="tab-content hidden">
        <form method="POST" action="{{ route('settings.appearance.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="logo" class="block text-gray-700 font-medium mb-1">Upload Logo</label>
                <input type="file" name="logo" id="logo"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('logo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="theme_color" class="block text-gray-700 font-medium mb-1">Theme Color</label>
                <input type="color" name="theme_color" id="theme_color" value="{{ old('theme_color', session('theme_color', '#4F46E5')) }}"
                    class="w-20 h-10 p-0 border-0 cursor-pointer" />
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Save Appearance</button>
        </form>
    </div>
</div>

<script>
  // Simple tab switching
  const tabs = document.querySelectorAll('.tab-link');
  const contents = document.querySelectorAll('.tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
      e.preventDefault();

      tabs.forEach(t => t.classList.remove('border-indigo-600', 'text-indigo-600'));
      tabs.forEach(t => t.classList.add('text-gray-600'));

      tab.classList.add('border-indigo-600', 'text-indigo-600');
      tab.classList.remove('text-gray-600');

      const tabName = tab.getAttribute('data-tab');
      contents.forEach(c => {
        if (c.id === tabName) {
          c.classList.remove('hidden');
        } else {
          c.classList.add('hidden');
        }
      });
    });
  });

  // Set default active tab
  tabs[0].click();
</script>

@endsection