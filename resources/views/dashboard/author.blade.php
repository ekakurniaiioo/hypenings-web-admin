@extends('layouts.app')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-bold text-black">Hai {{ auth()->user()->name }} 👋</h1>
            <p class="text-gray-500 mb-8">Selamat datang di dashboard Author. Lihat performa artikelmu di Hypenings!</p>
            <h1 class="text-2xl font-bold mb-6 text-gray-800">📖 Dashboard Author</h1>
        </div>

        {{-- Statistik singkat --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('articles.index', ['author' => auth()->id(), 'is_shorts' => 0]) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Artikel Kamu</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $myArticlesCount }}</h2>
                <p class="text-xs text-gray-400 mt-1">Klik untuk lihat semua</p>
            </a>

            <a href="{{ route('articles.index', ['author' => auth()->id(), 'is_shorts' => 1]) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Shorts Kamu</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $myShortsCount }}</h2>
                <p class="text-xs text-gray-400 mt-1">Klik untuk lihat shorts</p>
            </a>

            <a href="{{ route('articles.index') }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Ayo berkarya</p>
                <h2 class="text-xl font-bold text-yellow-600">+ Buat Artikel Baru</h2>
            </a>
        </div>

        {{-- Chart --}}
        <div class="bg-white p-6 rounded-xl shadow mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Author Uploads (Last 7 Days)</h2>
            <canvas id="authorArticlesChart" height="100"></canvas>
        </div>

        {{-- Recent articles --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Artikel Terbaru Kamu</h2>
            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 border text-left">Judul</th>
                        <th class="px-4 py-2 border text-center">Views</th>
                        <th class="px-4 py-2 border text-center">Tanggal</th>
                        <th class="px-4 py-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($myRecentArticles as $article)
                        <tr>
                            <td class="px-4 py-2 border">{{ $article->title }}</td>
                            <td class="px-4 py-2 border text-center">{{ $article->views }}</td>
                            <td class="px-4 py-2 border text-center">{{ $article->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-2 border text-center">
                                @can('update', $article)
                                    <a href="{{ route('articles.edit', $article->id) }}" class="text-blue-500">Edit</a>
                                @endcan
                                @can('delete', $article)
                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-2"
                                            onclick="return confirm('Hapus artikel ini?')">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 border text-center text-gray-500">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxAuthor = document.getElementById('authorArticlesChart').getContext('2d');
        new Chart(ctxAuthor, {
            type: 'line',
            data: {
                labels: @json($authorChartLabels),
                datasets: [{
                    label: 'Articles Uploaded',
                    data: @json($authorChartData),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection