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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @if(auth()->user()->isSuperAdmin())
                <a href="/users" class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                    <p class="text-gray-500">Manage</p>
                    <h2 class="text-xl font-bold text-purple-600">Kelola User</h2>
                    <p class="text-xs text-gray-400 mt-1">Only Superadmin</p>
                </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                <a href="/articles" class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                    <p class="text-gray-500">Manage</p>
                    <h2 class="text-xl font-bold text-blue-600">Kelola Artikel</h2>
                    <p class="text-xs text-gray-400 mt-1">Admin & Superadmin</p>
                </a>
            @endif

            @if(auth()->user()->isEditor())
                <a href="/editor" class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                    <p class="text-gray-500">Manage</p>
                    <h2 class="text-xl font-bold text-green-600">Editor Panel</h2>
                    <p class="text-xs text-gray-400 mt-1">Only Editor</p>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('articles.index', ['is_shorts' => 0]) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Total Articles</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $totalNews }}</h2>
                <p class="text-xs text-gray-400 mt-1">Click to see all articles</p>
            </a>

            <a href="{{ route('articles.index', ['is_shorts' => 1]) }}"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Total Shorts</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $totalShorts }}</h2>
                <p class="text-xs text-gray-400 mt-1">Click to see all shorts</p>
            </a>

            {{-- Total User --}}
            <div id="totalUserCard"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition cursor-pointer relative overflow-hidden">
                <p class="text-gray-500">Total User</p>
                <h2 class="text-3xl font-bold text-purple-600">{{ $totalUsers }}</h2>
                <p class="text-xs text-gray-400 mt-1">Click to see all username</p>

                {{-- Username List --}}
                <div id="userList"
                    class="max-h-0 overflow-hidden opacity-0 transform scale-95 transition-all duration-500 ease-in-out mt-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-purple-100 shadow-inner">
                        <h3 class="text-lg font-semibold mb-3 text-purple-700">Username List</h3>
                        <ul class="divide-y divide-gray-200 max-h-48 overflow-y-auto">
                            @foreach($users as $user)
                                <li class="py-2 text-gray-700">{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Total Category --}}
            <div id="totalCategoryCard"
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition cursor-pointer relative overflow-hidden">
                <p class="text-gray-500">Total Category</p>
                <h2 class="text-3xl font-bold text-yellow-600">{{ $totalCategories }}</h2>
                <p class="text-xs text-gray-400 mt-1">Click to see category details</p>

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

        <div class="bg-white p-6 rounded-xl shadow mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">News Uploads (Last 7 Days)</h2>
            <canvas id="articlesChart" height="100"></canvas>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Recent Activity</h2>
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
        document.getElementById('totalUserCard').addEventListener('click', function () {
            const userList = document.getElementById('userList');
            userList.classList.toggle('max-h-0');
            userList.classList.toggle('opacity-0');
            userList.classList.toggle('scale-95');
            userList.classList.toggle('max-h-[500px]'); // tinggi maksimal saat expand
            userList.classList.toggle('opacity-100');
            userList.classList.toggle('scale-100');
        });

        document.getElementById('totalCategoryCard').addEventListener('click', function () {
            const categoryMetrics = document.getElementById('categoryMetrics');
            categoryMetrics.classList.toggle('max-h-0');
            categoryMetrics.classList.toggle('opacity-0');
            categoryMetrics.classList.toggle('scale-95');
            categoryMetrics.classList.toggle('max-h-[500px]');
            categoryMetrics.classList.toggle('opacity-100');
            categoryMetrics.classList.toggle('scale-100');
        });

        const ctx = document.getElementById('articlesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'News Per Day',
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