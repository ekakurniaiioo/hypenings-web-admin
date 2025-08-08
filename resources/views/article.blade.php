@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Notifikasi sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow-sm mb-6">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📰 Article Management</h1>
            <button onclick="document.getElementById('addArticleModal').classList.remove('hidden')"
                class="bg-yellow-400 text-gray-900 hover:bg-yellow-500 transition px-5 py-2 rounded-lg font-medium flex items-center shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Article
            </button>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Id</th>
                            <th class="px-6 py-3 text-left">Category</th>
                            <th class="px-6 py-3 text-left">Title</th>
                            <th class="px-6 py-3 text-left">Description</th>
                            <th class="px-6 py-3 text-left">Image</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Content For</th>
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
                                <td class="px-6 py-4 text-xs text-gray-600 max-w-[220px] line-clamp-1">{{ $item->content }}</td>
                                <td class="px-6 py-4">
                                    {{-- Gambar utama --}}
                                    @if($item->image && (!$item->slider || !$item->slider->media->count()))
                                        <div class="w-[200px] h-[240px] rounded-lg overflow-hidden shadow">
                                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif

                                    {{-- Slider --}}
                                    @if ($item->slider && $item->slider->media->count())
                                        <div class="swiper mySwiper w-[200px] h-[240px] mt-2 rounded-lg overflow-hidden shadow">
                                            <div class="swiper-wrapper">
                                                @foreach ($item->slider->media as $media)
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
                                <td class="px-12 py-4 text-center">
                                    <a href="{{ route('articles.edit', $item->id) }}" class="text-blue-600 hover:underline">
                                        ✏️ Edit
                                    </a>
                                    <span class="text-gray-400 mx-1">|</span>
                                    <form action="{{ route('articles.destroy', $item->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500">No articles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Add Article --}}
    <div id="addArticleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Article</h3>
                <button onclick="document.getElementById('addArticleModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" required class="w-full border p-2 rounded">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" required class="w-full border p-2 rounded">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="content" rows="4" required class="w-full border p-2 rounded"></textarea>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="published_at" required class="w-full border p-2 rounded">
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Article Flags</label>
                    <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded-lg border shadow-sm">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_trending" value="1" class="form-checkbox text-indigo-600">
                            <span class="text-gray-700">Trending</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_topic" value="1" class="form-checkbox text-indigo-600">
                            <span class="text-gray-700">Topik</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_featured_slider" value="1"
                                class="form-checkbox text-indigo-600">
                            <span class="text-gray-700">Featured Slider</span>
                        </label>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_shorts" id="is_shorts" value="1"
                                class="form-checkbox text-indigo-600">
                            <span class="text-gray-700">Shorts</span>
                        </label>

                        <div id="videoInput" class="mt-4 hidden">
                            <label for="video_path" class="block text-sm font-medium text-gray-700">Upload Video
                                (Shorts)</label>
                            <input type="file" name="video_path" id="video_path" accept="video/*"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div id="slider-images-section" class="hidden mt-4">
                            <label for="slider_images" class="block text-sm font-medium text-gray-700 mb-1">
                                Upload Slider Images / Videos
                            </label>
                            <input type="file" id="slider_images" name="slider_images[]" multiple accept="image/*,video/*"
                                class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4
                                                               file:rounded-lg file:border-0
                                                               file:text-sm file:font-semibold
                                                               file:bg-yellow-400 file:text-white
                                                               hover:file:bg-yellow-500 transition">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('addArticleModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black rounded">Save</button>
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
        window.addEventListener('load', function () {
            // Toggle video input
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
                const files = Array.from(input.files); // ini array File sesuai urutan pemilihan

                // Tampilkan urutan nama file (opsional untuk debugging)
                console.log("Urutan file:", files.map(f => f.name));

                // Kalau kamu pakai AJAX (misalnya pakai fetch):
                const formData = new FormData();

                files.forEach((file, index) => {
                    formData.append('slider_images[]', file); // urutan dipertahankan
                });

                // Kirim via fetch (contoh)
                // fetch('/upload-slider', {
                //     method: 'POST',
                //     body: formData
                // });

                // Atau biarkan form biasa submit, lalu kamu urutkan berdasarkan array input.files
            });

            // Initialize Swiper for each .mySwiper
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