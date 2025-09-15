@extends('layouts.app')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
                👤 Dashboard User
            </h1>
            <p class="text-gray-600">Welcome back to <span class="font-semibold text-blue-600">Hypenings</span>!
                Temukan berita terbaru dan trending hanya untukmu.</p>
        </div>

        {{-- Menu Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <a href="{{ route('articles.index', ['is_shorts' => 0]) }}"
                class="bg-white p-6 rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        📖
                    </div>
                    <h2 class="text-2xl font-bold text-blue-600">{{ $totalNews }}</h2>
                    <h2 class="text-xl font-bold text-blue-700">Artikel</h2>
                </div>
                <p class="text-gray-500 text-sm">Lihat semua artikel yang tersedia.</p>
            </a>

            <a href="{{ route('articles.index', ['is_shorts' => 1]) }}"
                class="bg-white p-6 rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        ⚡
                    </div>
                    <h2 class="text-2xl font-bold text-green-600">{{ $totalShorts }}</h2>
                    <h2 class="text-xl font-bold text-green-700">Shorts</h2>
                </div>
                <p class="text-gray-500 text-sm">Baca ringkasan berita singkat dengan cepat.</p>
            </a>

            <div id="totalCategoryCard"
                class="bg-white p-6 rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                        🔖
                    </div>
                    <h2 class="text-3xl font-bold text-yellow-600">{{ $totalCategories }}</h2>
                    <p class="text-xl font-bold text-purple-700">Category</p>
                </div>
                <p class="text-gray-500 text-sm">Click to see category details</p>

                {{-- Category List --}}
                <div id="categoryMetrics"
                    class="max-h-0 overflow-hidden opacity-0 transform scale-95 transition-all duration-500 ease-in-out mt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categories as $category)
                            <a href="{{ route('articles.index', ['category' => $category->id]) }}"
                                class="block bg-gray-50 p-4 rounded-lg border border-yellow-100 shadow-inner hover:shadow-md transition transform hover:-translate-y-1">
                                <p class="text-sm text-gray-500 capitalize">{{ $category->name }}</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $category->articles_count }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Artikel Terbaru --}}
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📰 Artikel Terbaru</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($latestArticles as $article)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                            class="w-full h-80 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $article->title }}</h3>
                            <p class="text-gray-500 text-sm line-clamp-2">{{ $article->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Statistik kecil --}}
        <div class="grid grid-cols-2 sm:grid-cols-1 gap-6">
            <div class="bg-white p-5 rounded-2xl shadow text-center">
                <h3 class="text-2xl font-bold text-yellow-600">{{ $usersCount }}</h3>
                <p class="text-gray-500 text-sm">Users</p>
                <p class="text-gray-500 text-sm">Only SuperAdmin can access this</p>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('totalCategoryCard').addEventListener('click', function () {
            const categoryMetrics = document.getElementById('categoryMetrics');
            categoryMetrics.classList.toggle('max-h-0');
            categoryMetrics.classList.toggle('opacity-0');
            categoryMetrics.classList.toggle('scale-95');
            categoryMetrics.classList.toggle('max-h-[500px]');
            categoryMetrics.classList.toggle('opacity-100');
            categoryMetrics.classList.toggle('scale-100');
        });
    </script>
@endsection