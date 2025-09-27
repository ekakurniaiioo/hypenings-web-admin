@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Settings</h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <div class="border-b border-gray-300 mb-6">
            <nav class="flex space-x-6" aria-label="Tabs" id="tabs">
                <a href="#profile" class="tab-link px-3 py-2 font-medium text-indigo-600 border-b-2 border-indigo-600"
                    data-tab="profile">Profile</a>
                <a href="#security" class="tab-link px-3 py-2 font-medium text-gray-600 hover:text-indigo-600"
                    data-tab="security">Security</a>
            </nav>
        </div>

        {{-- Profile Settings --}}
        <div id="profile" class="tab-content">
            <form method="POST" action="{{ route('settings.profile.update') }}">
                @csrf
                {{-- Profile Avatar --}}
                <div class="flex flex-col items-center mb-6">
                    <label for="name" class="block text-gray-700 text-xl font-bold mb-4">Profile</label>
                    <div class="relative">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('image/user.jpg') }}"
                            alt="avatar"
                            class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 shadow-md hover:ring-4 hover:ring-indigo-400 transition duration-300" />
                    </div>
                    <div class="mt-4 items-center">
                        {{-- Badge Role --}}
                        @php
                            $roleColors = [
                                'superadmin' => 'bg-red-100 text-red-700',
                                'admin' => 'bg-blue-100 text-blue-700',
                                'editor' => 'bg-purple-100 text-purple-700',
                                'author' => 'bg-green-100 text-green-700',
                                'user' => 'bg-gray-100 text-gray-700',
                            ];
                          @endphp
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>

                {{-- Input Fields --}}
                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-1">Change Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter a new name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('name') border-red-500 @enderror" />
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-1">Change Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter a new email"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email') border-red-500 @enderror" />
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-8 text-center">
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium shadow-md hover:bg-indigo-700 transition duration-300">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        {{-- Security Settings --}}
        <div id="security" class="tab-content hidden">
            <form method="POST" action="{{ route('settings.security.update') }}"
                class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-md">
                @csrf
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Change Password</h2>

                {{-- Current Password --}}
                <div class="mb-5">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Current Password
                    </label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('current_password') border-red-500 @enderror"
                        placeholder="Enter your current password" />
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        New Password
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                        placeholder="Enter a new password" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Re-type new password" />
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-lg shadow-md transition duration-200">
                    Update Password
                </button>
            </form>
        </div>

    </div>

    <script>
        // Simple tab switching
        const tabs = document.querySelectorAll('.tab-link');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener("click", function (e) {
                e.preventDefault();

                // reset semua tab ke default
                tabs.forEach(t => t.classList.remove(
                    "text-indigo-600", "border-indigo-600"
                ));
                tabs.forEach(t => t.classList.add(
                    "text-gray-600", "hover:text-indigo-600"
                ));

                // aktifkan tab yang diklik
                this.classList.remove("text-gray-600", "hover:text-indigo-600");
                this.classList.add("text-indigo-600", "border-b-2", "border-indigo-600");

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
    </script>

@endsection