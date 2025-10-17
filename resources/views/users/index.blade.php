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
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-trigger-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium shadow transition"
                                            data-form-id="delete-form-{{ $user->id }}">
                                            Hapus
                                        </button>

                                        <div id="deleteModal"
                                            class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity">

                                            <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-xl border border-white/10 max-w-sm w-full p-6 text-center scale-95 opacity-0 transition-all duration-200"
                                                id="modalContent">

                                                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                                    Delete Confirmation
                                                </h3>

                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                                                    Are you sure you want to delete this item? This action cannot be
                                                    undone.
                                                </p>

                                                <div class="flex justify-center gap-3">
                                                    <button id="cancelBtn" type="button"
                                                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                                        Cancel
                                                    </button>

                                                    <button id="confirmDeleteBtn" type="button"
                                                        class="px-4 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 shadow-sm hover:shadow-md transition">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteTriggerButtons = document.querySelectorAll('.delete-trigger-btn');

        let formToSubmit = null;

        deleteTriggerButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const formId = event.currentTarget.getAttribute('data-form-id');
                formToSubmit = document.getElementById(formId);
                openDeleteModal();
            });
        });

        function openDeleteModal() {
            const modalContent = document.getElementById('modalContent');

            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');

            setTimeout(() => {
                modalContent.classList.remove('opacity-0', 'scale-95');
                modalContent.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modalContent = document.getElementById('modalContent');

            modalContent.classList.add('opacity-0', 'scale-95');
            modalContent.classList.remove('opacity-100', 'scale-100');

            setTimeout(() => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            }, 150);
        }

        cancelBtn.addEventListener('click', hideModal);
        confirmDeleteBtn.addEventListener('click', () => {
            if (formToSubmit) formToSubmit.submit();
        });

        function hideModal() {
            closeDeleteModal();
            formToSubmit = null;
        }

        window.addEventListener('click', (event) => {
            if (event.target === deleteModal) hideModal();
        });
    </script>

@endsection