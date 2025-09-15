@extends('layouts.app')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-black">Hai {{ auth()->user()->name }} 👋</h1>
            <p class="text-gray-500 mb-8">Selamat datang di dashboard Editor. Kelola dan review artikel Hypenings!</p>
            <h1 class="text-2xl font-bold mb-6 text-gray-800">✍️ Dashboard Editor</h1>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('articles.index', ['status' => 'pending']) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Artikel Pending</p>
                <h2 class="text-3xl font-bold text-yellow-600">{{ $pendingArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Menunggu review</p>
            </a>

            <a href="{{ route('articles.index', ['status' => 'approved']) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Artikel Disetujui</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $approvedArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Sudah dipublikasikan</p>
            </a>

            <a href="{{ route('articles.index', ['status' => 'rejected']) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Artikel Ditolak</p>
                <h2 class="text-3xl font-bold text-red-600">{{ $rejectedArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Butuh revisi</p>
            </a>

            <a href="{{ route('articles.index') }}" class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Total Artikel</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $totalArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Semua artikel</p>
            </a>
        </div>

        {{-- Grafik aktivitas --}}
        <div class="bg-white p-6 rounded-xl shadow mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Artikel Direview (7 Hari Terakhir)</h2>
            <canvas id="editorArticlesChart" height="100"></canvas>
        </div>

        {{-- Artikel terbaru untuk direview --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Artikel Pending Review</h2>
            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 border text-left">Judul</th>
                        <th class="px-4 py-2 border text-center">Author</th>
                        <th class="px-4 py-2 border text-center">Tanggal</th>
                        <th class="px-4 py-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingList as $article)
                        <tr>
                            <td class="px-4 py-2 border">{{ $article->title }}</td>
                            <td class="px-4 py-2 border text-center">{{ $article->user->name }}</td>
                            <td class="px-4 py-2 border text-center">{{ $article->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-2 border text-center">
                                <a href="{{ route('articles.review', $article->id) }}"
                                    class="text-blue-500 font-medium hover:underline">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 border text-center text-gray-500">Tidak ada artikel pending.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('editorArticlesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Artikel Direview',
                    data: @json($chartData),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
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