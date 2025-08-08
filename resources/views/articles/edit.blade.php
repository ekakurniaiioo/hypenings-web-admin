@extends('layouts.app')

@section('content')
  <div class="max-w-3xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Artikel</h1>

    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data"
    class="space-y-6 bg-white p-6 rounded-lg shadow-md">
    @csrf
    @method('PUT')

    {{-- Category --}}
    <div>
      <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
      <select name="category_id" id="category_id" required
      class="w-full border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
      <option value="">Pilih Kategori</option>
      @foreach($categories as $category)
      <option value="{{ $category->id }}" {{ $category->id == $article->category_id ? 'selected' : '' }}>
      {{ $category->name }}
      </option>
    @endforeach
      </select>
    </div>

    {{-- Title --}}
    <div>
      <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
      <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required
      class="w-full border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
    </div>

    {{-- Content --}}
    <div>
      <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
      <textarea name="content" id="content" rows="4" required
      class="w-full border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">{{ old('content', $article->content) }}</textarea>
    </div>

    {{-- Image --}}
    <div>
      <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
      <input type="file" name="image" id="image" accept="image/*"
      class="w-full border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
      @if($article->image_path)
      <img src="{{ asset('storage/' . $article->image_path) }}" alt="Current Image" class="mt-3 w-32 rounded shadow-sm">
    @endif
    </div>

    {{-- Date --}}
    <div>
      <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
      <input type="date" name="published_at" id="published_at"
      value="{{ old('published_at', \Carbon\Carbon::parse($article->published_at)->format('Y-m-d')) }}" required
      class="w-full border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
    </div>

    {{-- Article Flags --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Artikel</label>
      <div class="grid grid-cols-2 gap-3 p-4 bg-gray-50 rounded-lg border">
      @php
      $flags = [
      ['is_trending', 'Trending'],
      ['is_topic', 'Topik'],
      ['is_slider', 'Featured Slider'],
      ['is_shorts', 'Shorts']
      ];
    @endphp

      @foreach($flags as [$name, $label])
      <label class="flex items-center space-x-2">
      <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1"
      class="form-checkbox text-yellow-500 rounded focus:ring-yellow-400" {{ $article->$name ? 'checked' : '' }}>
      <span class="text-gray-700">{{ $label }}</span>
      </label>
    @endforeach
      </div>
    </div>

    {{-- Video Input (Shorts) --}}
    <div id="videoInput" class="transition-all duration-300 ease-in-out {{ $article->is_shorts ? 'block' : 'hidden' }}">
      <label for="video_path" class="block text-sm font-medium text-gray-700 mb-1">Upload Video (Shorts)</label>
      <input type="file" name="video_path" id="video_path" accept="video/*"
      class="w-full border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
      @if($article->video_path)
      <video src="{{ asset('storage/' . $article->video_path) }}" class="mt-3 w-48 rounded shadow-sm" controls></video>
    @endif
    </div>

    {{-- Slider Images Section --}}
    <div id="slider-images-section"
      class="transition-all duration-300 ease-in-out {{ $article->is_slider || $article->is_trending || $article->is_topic ? 'block' : 'hidden' }}">
      <label for="slider_images" class="block text-sm font-medium text-gray-700 mb-1">Upload Slider Images /
      Videos</label>
      <input type="file" id="slider_images" name="slider_images[]" multiple accept="image/*,video/*"
      class="block w-full text-sm text-gray-900 border rounded p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">

      @if ($article->slider && $article->slider->sliderMedia)
      <div class="grid grid-cols-2 gap-2 mt-3">
      @foreach ($article->slider->sliderMedia as $media)
      @if (Str::endsWith($media->path, ['.mp4', '.webm']))
      <video src="{{ asset('storage/' . $media->path) }}" controls
      class="w-32 h-24 object-cover rounded shadow-sm"></video>
      @else
      <img src="{{ asset('storage/' . $media->path) }}" class="w-32 h-24 object-cover rounded shadow-sm"
      alt="Slider Media">
      @endif
    @endforeach
      </div>
    @endif
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-end space-x-3">
      <a href="{{ route('articles.index') }}"
      class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">Batal</a>
      <button type="submit"
      class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black rounded transition">Simpan</button>
    </div>
    </form>
  </div>

  {{-- Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
    const shortsCheckbox = document.getElementById('is_shorts');
    const videoInput = document.getElementById('videoInput');

    const sliderCheckbox = document.getElementById('is_slider');
    const topicCheckbox = document.getElementById('is_topic');
    const trendingCheckbox = document.getElementById('is_trending');
    const sliderSection = document.getElementById('slider-images-section');

    const toggleSection = (checkbox, section) => {
      section.classList.toggle('hidden', !checkbox.checked);
    };

    shortsCheckbox.addEventListener('change', () => toggleSection(shortsCheckbox, videoInput));

    const toggleSliderSection = () => {
      const showSlider = sliderCheckbox.checked || topicCheckbox.checked || trendingCheckbox.checked;
      sliderSection.classList.toggle('hidden', !showSlider);
    };

    [sliderCheckbox, topicCheckbox, trendingCheckbox].forEach(cb => cb.addEventListener('change', toggleSliderSection));
    });
  </script>
@endsection