@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Manajemen User</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-xl overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-sm uppercase tracking-wide">
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Avatar</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- No -->
                            <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Avatar -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('image/user.jpg') }}"
                                        alt="Avatar"
                                        class="w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm" />
                                </div>
                            </td>

                            <!-- Name -->
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                {{ $user->name }}
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $user->email }}
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
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
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    {{-- Update Role --}}
                                    <form action="{{ route('users.updateRole', $user->id) }}" method="POST"
                                        class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')

                                        <select name="role"
                                            class="border border-gray-300 rounded-lg text-sm px-2 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                            <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Super
                                                Admin</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor
                                            </option>
                                            <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>Author
                                            </option>
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        </select>

                                        <button type="submit"
                                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow transition">
                                            Update
                                        </button>
                                    </form>

                                    {{-- Delete User --}}
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus user ini?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection