@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Article Management</h1>
            <button onclick="document.getElementById('addArticleModal').classList.remove('hidden')"
                class="bg-yellow-300 text-black hover:bg-yellow-400 px-4 py-2 rounded-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Article
            </button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">is_trending</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">is_topic</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">is_featured_slider
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">is_shorts</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($articles as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $item->category->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->title }}</td>
                                <td class="px-6 py-4 text-xs text-gray-600 leading-tight max-w-[200px]">
                                    <div class="line-clamp-1">
                                        {{ $item->content }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Gambar utama: hanya tampil jika tidak ada slider --}}
                                    @if($item->image && (!$item->slider || !$item->slider->media->count()))
                                        <div class="w-[300px] h-[350px] rounded overflow-hidden mb-4">
                                            <img src="{{ asset($item->image) }}" alt="Gambar Artikel"
                                            class="w-full h-full object-cover rounded">
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No image</span>
                                    @endif

                                    {{-- Slider jika ada --}}
                                    @if ($item->slider && $item->slider->media->count())
                                        <div class="swiper mySwiper w-[300px] h-[350px] mt-2 rounded overflow-hidden">
                                            <div class="swiper-wrapper">
                                                @foreach ($item->slider->media as $media)
                                                    <div class="swiper-slide">
                                                        @if(Str::endsWith($media->file_path, ['.mp4', '.mov']))
                                                            <video controls class="w-full h-full object-cover rounded-xl">
                                                                <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                                            </video>
                                                        @else
                                                            <img src="{{ asset('storage/' . $media->file_path) }}"
                                                                class="w-full h-full object-cover rounded-lg">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Navigasi slider --}}
                                            <div class="swiper-button-next"></div>
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-pagination"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($item->published_at)->format('F j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">{{ $item->is_trending }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->is_topic }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->is_featured_slider }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->is_shorts }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('articles.edit', $item->id) }}"
                                        class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="{{ route('articles.destroy', $item->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-3">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-gray-500">No articles found.</td>
                            </tr>
                        @endforelse
                        @if(request('category'))
                            <div class="mb-4 text-sm text-gray-600">
                                Menampilkan artikel kategori:
                                <strong>{{ $categories->find(request('category'))?->name ?? 'Tidak ditemukan' }}</strong>
                            </div>
                        @endif
                    </tbody>
                </table>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
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
                                <input type="checkbox" name="is_slider" value="1" class="form-checkbox text-indigo-600">
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
                                <input type="file" id="slider_images" name="slider_images[]" multiple
                                    accept="image/*,video/*" class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4
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

                const sliderCheckbox = document.querySelector('input[name="is_slider"]');
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