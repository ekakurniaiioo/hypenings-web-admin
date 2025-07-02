@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800">Welcome to Admin Dashboard</h2>
        <p class="text-gray-600">This is a sample dashboard page. You can customize it as needed.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Getting Started</h3>
        <p class="text-gray-600">
            This is a starter template for your admin dashboard. You can modify and extend it based on your requirements.
        </p>
        
        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Go to Dashboard
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
@endsection
