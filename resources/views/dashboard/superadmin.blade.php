@extends('layouts.app')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">📊 SuperAdmin Dashboard</h1>
            <p class="text-gray-500 mt-2">Manage everything about Hypenings in one place.</p>
        </div>

        {{-- Management Quick Links --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <a href="/users" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-sm text-gray-500">Management</p>
                <h2 class="text-xl font-bold text-purple-600">User Management</h2>
                <p class="text-xs text-gray-400 mt-1">Admins & Superadmins</p>
            </a>

            <a href="/articles" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-sm text-gray-500">Management</p>
                <h2 class="text-xl font-bold text-blue-600">Article Management</h2>
                <p class="text-xs text-gray-400 mt-1">Admins & Superadmins</p>
            </a>

            <a href="#review-article" class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-sm text-gray-500">Review</p>
                <h2 class="text-xl font-bold text-yellow-600">Pending Reviews</h2>
                <p class="text-xs text-gray-400 mt-1">Editors, Admins & Superadmins</p>
            </a>
        </div>

        {{-- Overview Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <a href="{{ route('articles.index', ['is_shorts' => 0]) }}"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Total Articles</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $totalNews }}</h2>
                <p class="text-xs text-gray-400 mt-1">View all articles</p>
            </a>

            <a href="{{ route('articles.index', ['is_shorts' => 1]) }}"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Total Shorts</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $totalShorts }}</h2>
                <p class="text-xs text-gray-400 mt-1">View all shorts</p>
            </a>

            {{-- User List --}}
            <div id="totalUserCard"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition cursor-pointer relative overflow-hidden">
                <p class="text-gray-500">Total Users</p>
                <h2 class="text-3xl font-bold text-purple-600">{{ $totalUsers }}</h2>
                <p class="text-xs text-gray-400 mt-1">Click to see usernames</p>

                <div id="userList"
                    class="max-h-0 overflow-hidden opacity-0 transform scale-95 transition-all duration-500 ease-in-out mt-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-purple-100 shadow-inner">
                        <h3 class="text-lg font-semibold mb-3 text-purple-700">User List</h3>
                        <ul class="divide-y divide-gray-200 max-h-48 overflow-y-auto">
                            @foreach($users as $user)
                                <li class="py-2 text-gray-700">{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Category List --}}
            <div id="totalCategoryCard"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition cursor-pointer relative overflow-hidden">
                <p class="text-gray-500">Total Categories</p>
                <h2 class="text-3xl font-bold text-yellow-600">{{ $totalCategories }}</h2>
                <p class="text-xs text-gray-400 mt-1">Click to see categories</p>

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

        {{-- Chart --}}
        <div class="bg-white p-6 rounded-xl shadow mb-12">
            <h2 class="text-lg font-semibold mb-4 py-4 border-b text-gray-700">Articles Uploads (Last 7 Days)</h2>
            <canvas id="articlesChart" height="100"></canvas>
        </div>

        {{-- Activity + Top Articles --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4 py-4 border-b text-gray-700">Recent Activities</h2>
                <ul class="space-y-4">
                    @forelse ($recentActivities as $activity)
                        <li class="border-b pb-2">
                            <strong class="text-gray-800">{{ $activity->title }}</strong>
                            <p class="text-sm text-gray-500">{{ $activity->message }}</p>
                            <small class="text-gray-400">{{ $activity->created_at->diffForHumans() }}</small>
                        </li>
                    @empty
                        <li class="text-gray-500">No recent activity available.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4 py-4 border-b text-gray-700">Top Articles This Month</h2>
                <table class="min-w-full border rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 border text-left">Title</th>
                            <th class="px-4 py-2 border text-center">Views</th>
                            <th class="px-4 py-2 border text-center">Date</th>
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
                                <td colspan="3" class="px-4 py-2 border text-center text-gray-500">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Article Status Overview --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <a href="{{ route('articles.index', ['status' => 'pending']) }}"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Pending Articles</p>
                <h2 class="text-3xl font-bold text-yellow-600">{{ $pendingArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Awaiting review</p>
            </a>

            <a href="{{ route('articles.index', ['status' => 'approved']) }}"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Approved Articles</p>
                <h2 class="text-3xl font-bold text-green-600">{{ $approvedArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Published</p>
            </a>

            <a href="{{ route('articles.index', ['status' => 'rejected']) }}"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Rejected Articles</p>
                <h2 class="text-3xl font-bold text-red-600">{{ $rejectedArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">Need revision</p>
            </a>

            <a href="{{ route('articles.index') }}"
                class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">
                <p class="text-gray-500">Total Articles</p>
                <h2 class="text-3xl font-bold text-blue-600">{{ $totalArticles }}</h2>
                <p class="text-xs text-gray-400 mt-1">All articles</p>
            </a>
        </div>

        {{-- Pending Review Section --}}
        <div id="review-article" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4 py-4 border-b text-gray-700">Pending Articles for Review</h2>
            <table class="min-w-full border rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 border text-left">Title</th>
                        <th class="px-4 py-2 border text-center">Author</th>
                        <th class="px-4 py-2 border text-center">Date</th>
                        <th class="px-4 py-2 border text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingList as $article)
                        <tr class="hover:bg-gray-100">
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
                            <td colspan="4" class="px-4 py-2 border text-center text-gray-500">No pending articles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle user list
        document.getElementById('totalUserCard').addEventListener('click', function () {
            const userList = document.getElementById('userList');
            userList.classList.toggle('max-h-0');
            userList.classList.toggle('opacity-0');
            userList.classList.toggle('scale-95');
            userList.classList.toggle('max-h-[500px]');
            userList.classList.toggle('opacity-100');
            userList.classList.toggle('scale-100');
        });

        // Toggle category list
        document.getElementById('totalCategoryCard').addEventListener('click', function () {
            const categoryMetrics = document.getElementById('categoryMetrics');
            categoryMetrics.classList.toggle('max-h-0');
            categoryMetrics.classList.toggle('opacity-0');
            categoryMetrics.classList.toggle('scale-95');
            categoryMetrics.classList.toggle('max-h-[500px]');
            categoryMetrics.classList.toggle('opacity-100');
            categoryMetrics.classList.toggle('scale-100');
        });

        // Chart
        const ctx = document.getElementById('articlesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Articles per Day',
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
