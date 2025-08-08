@extends('layouts.app')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">

        <div>
            <h1 class="text-2xl font-bold text-black">
                Ready to publish something new?
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mb-12 mt-2">Everything you need to manage Hypenings, right here!</p>
            <h1 class="text-2xl font-bold mb-6 text-gray-800">📊 Dashboard Admin</h1>
        </div>

        {{-- Statistik Card --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
                <p class="text-gray-500">Total Berita</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $totalNews }}</h2>
            </div>
            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
                <p class="text-gray-500">Total Shorts</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $totalShorts }}</h2>
            </div>
            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
                <p class="text-gray-500">Total User</p>
                <h2 class="text-3xl font-bold text-purple-600">{{ $totalUsers }}</h2>
            </div>
            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
                <p class="text-gray-500">Total Kategori</p>
                <h2 class="text-3xl font-bold text-yellow-600">{{ $totalCategories }}</h2>
            </div>
        </div>

        {{-- Grafik Artikel per Hari --}}
        <div class="bg-white p-6 rounded-xl shadow mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Tren Artikel (30 Hari Terakhir)</h2>
            <canvas id="articlesChart" height="100"></canvas>
        </div>

        {{-- Recent Activity & Top Articles --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Recent Activity --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Aktivitas Terbaru</h2>
                <ul class="space-y-4">
                    @forelse ($recentActivities as $activity)
                        <li class="border-b pb-2">
                            <strong class="text-gray-800">{{ $activity->title }}</strong>
                            <p class="text-sm text-gray-500">{{ $activity->message }}</p>
                            <small class="text-gray-400">{{ $activity->created_at->diffForHumans() }}</small>
                        </li>
                    @empty
                        <li class="text-gray-500">Belum ada aktivitas terbaru.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Top Articles --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Artikel Terpopuler Bulan Ini</h2>
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 border text-left">Judul</th>
                            <th class="px-4 py-2 border text-center">Views</th>
                            <th class="px-4 py-2 border text-center">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topArticles as $article)
                            <tr>
                                <td class="px-4 py-2 border">{{ $article->title }}</td>
                                <td class="px-4 py-2 border text-center">{{ $article->views }}</td>
                                <td class="px-4 py-2 border text-center">{{ $article->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 border text-center text-gray-500">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('articlesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Artikel per Hari',
                    data: @json($chartData),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
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