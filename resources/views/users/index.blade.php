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

        <table class="min-w-full bg-white shadow rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th class="px-6 py-3 text-left">Id</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('users.updateRole', $user->id) }}" method="POST"
                                class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="border rounded px-2 py-1 text-sm">
                                    <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Super Admin
                                    </option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor</option>
                                    <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>Author</option>
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Update
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Yakin mau hapus user ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection