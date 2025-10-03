@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow-sm mb-6">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📰 Article Management</h1>

            @if(auth()->check() && auth()->user()->role !== 'user')
                <button onclick="document.getElementById('addArticleModal').classList.remove('hidden')"
                    class="bg-yellow-400 text-gray-900 hover:bg-yellow-500 transition px-5 py-2 rounded-lg font-medium flex items-center shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Article
                </button>
            @endif
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
            <div class="overflow-x-auto overflow-y-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Category</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-left">Image</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Content For</th>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($articles as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium">
                                    <span
                                        class="px-3 py-1 text-sm font-semibold rounded {{ $item->category->badge_color ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $item->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $item->title }}</td>
                                <td class="px-6 py-4 text-xs text-gray-600 max-w-[420px]">
                                    <p class="line-clamp-[15]">{{ $item->content }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Gambar utama --}}
                                    @if($item->image && (!$item->slider || !$item->slider->slidermedia->count()))
                                        <div class="w-[200px] h-[240px] rounded-lg overflow-hidden shadow">
                                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif

                                    {{-- Slider --}}
                                    @if ($item->slider && $item->slider->slidermedia->count())
                                        <div class="swiper mySwiper w-[200px] h-[240px] mt-2 rounded-lg overflow-hidden shadow">
                                            <div class="swiper-wrapper">
                                                @foreach ($item->slider->slidermedia as $media)
                                                    <div class="swiper-slide">
                                                        @if(Str::endsWith($media->file_path, ['.mp4', '.mov']))
                                                            <video controls class="w-full h-full object-cover rounded-lg">
                                                                <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                                            </video>
                                                        @else
                                                            <img src="{{ asset('storage/' . $media->file_path) }}"
                                                                class="w-full h-full object-cover rounded-lg">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="swiper-button-next"></div>
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-pagination"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->published_at)->format('F j, Y') }}
                                </td>
                                <td class="px-6 py-4 space-y-1">
                                    @php
                                        $contentTypes = [];
                                        if ($item->is_trending)
                                            $contentTypes[] = 'Trending';
                                        if ($item->is_topic)
                                            $contentTypes[] = 'Topic';
                                        if ($item->is_featured_slider)
                                            $contentTypes[] = 'Featured Slider';
                                        if ($item->is_shorts)
                                            $contentTypes[] = 'Shorts';
                                    @endphp
                                    @forelse ($contentTypes as $type)
                                        <span
                                            class="inline-block px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 font-medium">{{ $type }}</span>
                                    @empty
                                        <span class="text-gray-400">-</span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->user->name ?? '-' }}
                                </td>
                                <td class="px-12 py-4 text-center">
                                    <div class="relative inline-block text-left">
                                        @can('update', $item)
                                            <div class="flex">
                                                <a href="{{ route('articles.review', $item->id) }}"
                                                    class="inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-gray-700 bg-blue-400 rounded-l-lg hover:opacity-[0.8] border-r border-gray-200 focus:outline-none">
                                                    Detail
                                                </a>
                                                <button type="button" onclick="toggleDropdown('dropdown-{{ $item->id }}')"
                                                    class="inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-gray-700 bg-blue-400 rounded-r-lg hover:opacity-[0.8] focus:outline-none">
                                                    ⋮
                                                </button>
                                            </div>

                                            <div id="dropdown-{{ $item->id }}"
                                                class="hidden absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-10">

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('articles.edit', $item->id) }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    ✏️ Edit
                                                </a>

                                                {{-- Tombol Delete --}}
                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('articles.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                        class="delete-trigger-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                        data-form-id="delete-form-{{ $item->id }}">
                                                        🗑️ Delete
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
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500">No articles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex flex-row justify-between items-center px-4 py-4 
                              bg-black/40 backdrop-blur-md border border-white/30 rounded-b-xl shadow-lg 
                                gap-0">

                    <form method="GET" class="flex items-center gap-2">
                        <label for="perPage" class="text-sm text-white drop-shadow-md">Show</label>
                        <select name="perPage" id="perPage" onchange="this.form.submit()" class="border border-white/40 bg-white/30 text-white rounded-md text-sm 
                           px-2 py-1 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 
                           transition backdrop-blur-md">
                            @foreach([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }} class="text-gray-800">
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-sm text-white drop-shadow-md">entries</span>
                    </form>

                    <!-- Pagination -->
                    <div class="mt-2 md:mt-0">
                        {{ $articles->appends(['perPage' => $perPage])->links('vendor.pagination.custom-glass') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add Article --}}
    <div id="addArticleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-6 w-full max-w-2xl shadow-lg rounded-xl bg-white">

            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add New Article</h3>
                <button onclick="toggleModal(false)" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" required
                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
                </div>

                <!-- Description -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="content" id="content" rows="4" required
                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none"></textarea>
                </div>

                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full border border-gray-300 p-2 rounded-lg file:bg-yellow-400 file:text-white file:font-semibold hover:file:bg-yellow-500 transition">
                </div>

                <!-- Date -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" required
                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
                </div>

                <!-- Flags -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Article Flags</label>
                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border">

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_trending" value="1" class="text-yellow-500 rounded">
                            <span>Trending</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_topic" value="1" class="text-yellow-500 rounded">
                            <span>Topik</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_featured_slider" id="is_featured_slider" value="1"
                                class="text-yellow-500 rounded">
                            <span>Featured Slider</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_shorts" id="is_shorts" value="1"
                                class="text-yellow-500 rounded">
                            <span>Shorts</span>
                        </label>

                        <!-- Shorts Video -->
                        <div id="videoInput" class="col-span-2 hidden">
                            <label for="video_path" class="block text-sm font-medium text-gray-700 mb-1">
                                Upload Video (Shorts)
                            </label>
                            <input type="file" name="video_path" id="video_path" accept="video/*"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg shadow-sm cursor-pointer
                                                                                                                                   file:mr-4 file:py-2 file:px-4
                                                                                                                                   file:rounded-lg file:border-0
                                                                                                                                   file:text-sm file:font-semibold
                                                                                                                                   file:bg-yellow-400 file:text-white
                                                                                                                                   hover:file:bg-yellow-500 transition">
                        </div>

                        <!-- Slider Images/Videos -->
                        <div id="slider-images-section" class="col-span-2 hidden">
                            <label for="slider_images" class="block text-sm font-medium text-gray-700 mb-1">Upload Slider
                                Images / Videos</label>
                            <input type="file" id="slider_images" name="slider_images[]" multiple accept="image/*,video/*"
                                class="block w-full text-sm border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4
                                                                                                                                      file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-400 file:text-white
                                                                                                                                      hover:file:bg-yellow-500 transition">
                        </div>

                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleModal(false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black rounded-lg transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .truncate {
            -webkit-line-clamp: 2;
        }
    </style>

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

        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle("hidden");
        }

        window.addEventListener("click", function (e) {
            document.querySelectorAll("[id^='dropdown-']").forEach(el => {
                if (!el.contains(e.target) && !e.target.closest("button")) {
                    el.classList.add("hidden");
                }
            });
        });


        function toggleModal(show) {
            const modal = document.getElementById('addArticleModal');
            if (show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        window.addEventListener('load', function () {
            const checkbox = document.getElementById('is_shorts');
            const videoInput = document.getElementById('videoInput');

            if (checkbox) {
                checkbox.addEventListener('change', function () {
                    videoInput.classList.toggle('hidden', !checkbox.checked);
                });
            }

            const sliderCheckbox = document.querySelector('input[name="is_featured_slider"]');
            const topicCheckbox = document.querySelector('input[name="is_topic"]');
            const trendingCheckbox = document.querySelector('input[name="is_trending"]');
            const sliderImagesSection = document.getElementById('slider-images-section');

            function toggleSliderSection() {
                const showSlider =
                    (sliderCheckbox && sliderCheckbox.checked) ||
                    (topicCheckbox && topicCheckbox.checked) ||
                    (trendingCheckbox && trendingCheckbox.checked);

                sliderImagesSection.classList.toggle('hidden', !showSlider);
            }

            if (sliderCheckbox && topicCheckbox && trendingCheckbox) {
                sliderCheckbox.addEventListener('change', toggleSliderSection);
                topicCheckbox.addEventListener('change', toggleSliderSection);
                trendingCheckbox.addEventListener('change', toggleSliderSection);

                toggleSliderSection();
            }

            document.getElementById('slider_images').addEventListener('change', function (event) {
                const input = event.target;
                const files = Array.from(input.files);

                console.log("Urutan file:", files.map(f => f.name));

                const formData = new FormData();

                files.forEach((file, index) => {
                    formData.append('slider_images[]', file);
                });

            });

            const swipers = document.querySelectorAll('.mySwiper');
            swipers.forEach(function (el) {
                new Swiper(el, {
                    loop: true,
                    navigation: {
                        nextEl: el.querySelector('.swiper-button-next'),
                        prevEl: el.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: el.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                });
            });

        });
    </script>

@endsection