@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
  <div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">{{ $news->title }}</h1>
      <div class="flex space-x-2">
        <a href="{{ route('news.edit', $news->id) }}" class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">
          Edit
        </a>
        <form action="{{ route('news.destroy', $news->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news item?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
            Delete
          </button>
        </form>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6">
        <div class="text-sm text-gray-500 mb-4">
          Posted on {{ $news->date->format('F j, Y') }}
        </div>

        <div class="prose max-w-none">
          {!! nl2br(e($news->description)) !!}
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200">
          <a href="{{ route('news.index') }}" class="text-indigo-600 hover:text-indigo-900">
            &larr; Back to News
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection