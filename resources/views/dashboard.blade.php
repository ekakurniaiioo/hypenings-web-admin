@extends('layouts.app')

@section('content')
<div class="mb-8">
  <h2 class="text-2xl font-semibold text-gray-800">Dashboard Overview</h2>
  <p class="text-gray-600">Welcome back! Here's what's happening with your store today.</p>
</div>

<!-- Stats Cards -->
<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
  <!-- Total News -->
  <div class="flex items-center p-4 bg-white rounded-lg shadow-xs">
    <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
    </div>
    <div>
      <p class="text-sm font-medium text-gray-600">Total News</p>
      <p class="text-lg font-semibold text-gray-700">12</p>
      <p class="text-xs text-green-600">+12% from last month</p>
    </div>
  </div>
</div>

@endsection