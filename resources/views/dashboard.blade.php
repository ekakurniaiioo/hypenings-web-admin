@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        
    @if (session('success'))
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <strong>Sukses!</strong> {{ session('success') }}
  </div>
@endif

        <!-- Judul dan Tombol -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Dashboard Overview</h2>
            <p class="text-gray-600">Welcome back! Here's what's happening with your store today.</p>
        </div>

        <div class="mb-6">
            <a href="{{ url('/news')}}"
                class="inline-block px-4 py-2 bg-yellow-300 text-black text-sm rounded-md shadow hover:bg-yellow-400">
                News Management
            </a>
        </div>

        <!-- Statistik -->
        <div class="grid gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex items-center p-4 bg-white rounded-lg shadow">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total News</p>
                    <p class="text-lg font-semibold text-gray-700">12</p>
                    <p class="text-xs text-green-600">+12% from last month</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-white rounded-lg shadow">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m3-4a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-lg font-semibold text-gray-700">8</p>
                    <p class="text-xs text-green-600">+1 user this week</p>
                </div>
            </div>
        </div>

        <!-- Statistik Kategori -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Content Metrics</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('articles.index', ['category' => $category->id]) }}"
                    class="block bg-white p-4 rounded-lg shadow text-center hover:bg-gray-50 transition">
                    <p class="text-sm text-gray-500">{{ $category->name }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $category->articles_count }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endsection