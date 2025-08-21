@extends('layouts.app')

@section('content')
  <div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">✏️ Edit Artikel</h1>

    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data"
    class="space-y-8 bg-white p-8 rounded-2xl shadow-lg">
    @csrf
    @method('PUT')

    {{-- CATEGORY --}}
    <div>
      <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
      <select name="category_id" id="category_id" required
      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
      <option value="">Pilih Kategori</option>
      @foreach($categories as $category)
      <option value="{{ $category->id }}" {{ $category->id == $article->category_id ? 'selected' : '' }}>
      {{ $category->name }}
      </option>
    @endforeach
      </select>
    </div>

    {{-- TITLE --}}
    <div>
      <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
      <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required
      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
    </div>

    {{-- CONTENT --}}
    <div>
      <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
      <textarea name="content" id="content" rows="4" required
      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">{{ old('content', $article->content) }}</textarea>
    </div>

    {{-- IMAGE --}}
    <div>
      <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar</label>
      <input type="file" name="image" id="image" accept="image/*"
      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
      @if($article->image_path)
      <div class="mt-3">
      <img src="{{ asset('storage/' . $article->image_path) }}" alt="Current Image"
      class="w-32 rounded-lg shadow-sm border">
      </div>
    @endif
    </div>

    {{-- DATE --}}
    <div>
      <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
      <input type="datetime-local" name="published_at" id="published_at"
      value="{{ old('published_at', \Carbon\Carbon::parse($article->published_at)->format('Y-m-d')) }}" required
      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-yellow-400 focus:outline-none">
    </div>

    {{-- FLAGS --}}
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-3">Opsi Artikel</label>
      <div class="grid grid-cols-2 gap-3 p-4 bg-gray-50 rounded-lg border">
      @php
      $flags = [
      ['is_trending', '🔥 Trending'],
      ['is_topic', '📌 Topik'],
      ['is_slider', '🖼️ Featured Slider'],
      ['is_shorts', '🎬 Shorts']
      ];
    @endphp

      @foreach($flags as [$name, $label])
      <label class="flex items-center space-x-2">
      <input type="hidden" name="{{ $name }}" value="0">
      <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1"
      class="form-checkbox text-yellow-500 rounded focus:ring-yellow-400" {{ $article->$name ? 'checked' : '' }}>
      <span class="text-gray-700 font-medium">{{ $label }}</span>
      </label>
    @endforeach
      </div>
    </div>

    {{-- VIDEO SHORTS --}}
    <div id="videoInput" class="transition-all duration-300 ease-in-out {{ $article->is_shorts ? 'block' : 'hidden' }}">
      <label for="video_path" class="block text-sm font-semibold text-gray-700 mb-3">🎬 Upload Video (Shorts)</label>

      <div
      class="border-2 border-dashed border-gray-300 rounded-xl p-5 hover:border-yellow-400 transition cursor-pointer bg-gray-50">
      <input type="file" name="video_path" id="video_path" accept="video/*"
        class="block w-full text-sm text-gray-700 focus:outline-none">
      <p class="text-xs text-gray-500 mt-2">Format: MP4 / WEBM — Maks 50MB</p>
      </div>

      @if($article->video_path)
      <div class="mt-4">
      <video src="{{ asset('storage/' . $article->video_path) }}" controls
      class="w-56 rounded-lg shadow-md border hover:scale-[1.02] transition duration-200"></video>
      </div>
    @endif
    </div>

    {{-- SLIDER IMAGES --}}
    <div id="slider-images-section"
      class="transition-all duration-300 ease-in-out {{ $article->is_slider || $article->is_trending || $article->is_topic ? 'block' : 'hidden' }}">
      <label for="slider_images" class="block text-sm font-semibold text-gray-700 mb-3">🖼️ Upload Slider Media</label>

      <div
      class="border-2 border-dashed border-gray-300 rounded-xl p-5 hover:border-yellow-400 transition cursor-pointer bg-gray-50">
      <input type="file" id="slider_images" name="slider_images[]" multiple accept="image/*,video/*"
        class="block w-full text-sm text-gray-700 focus:outline-none">
      <p class="text-xs text-gray-500 mt-2">Bisa pilih beberapa file gambar/video sekaligus</p>
      </div>

      @if ($article->slider && $article->slider->sliderMedia)
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
      @foreach ($article->slider->sliderMedia as $media)
      <div class="relative group overflow-hidden rounded-lg border shadow-sm hover:shadow-lg transition duration-200">
      @if (Str::endsWith($media->file_path, ['.mp4', '.webm']))
      <video src="{{ asset('storage/' . $media->file_path) }}" controls
      class="w-full h-full object-cover rounded-lg"></video>
      @else
      <img src="{{ asset('storage/' . $media->file_path) }}"
      class="w-full h-full object-cover rounded-lg" alt="Slider Media">
      @endif
      </div>
    @endforeach
      </div>
    @endif
    </div>


    {{-- ACTION BUTTONS --}}
    <div class="flex justify-end gap-3">
      <a href="{{ route('articles.index') }}"
      class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">Batal</a>
      <button type="submit"
      class="px-5 py-2 bg-yellow-400 hover:bg-yellow-500 text-black font-semibold rounded-lg transition">💾
      Simpan</button>
    </div>
    </form>
  </div>

  {{-- SCRIPT --}}
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

    [sliderCheckbox, topicCheckbox, trendingCheckbox].forEach(cb =>
      cb.addEventListener('change', toggleSliderSection)
    );
    });
  </script>
@endsection