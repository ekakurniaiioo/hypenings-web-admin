@extends('layouts.app')

@section('content')
  <div class="max-w-xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Edit Artikel</h1>

    <form action="{{ route('articles.update', $article->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
      <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
      <select name="category_id" required class="w-full border p-2 rounded">
      <option value="">Select Category</option>
      @foreach($categories as $category)
      <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
      </select>
    </div>


    <div>
      <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
      <input type="text" name="title" required class="w-full border p-2 rounded">
    </div>

    <div>
      <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
      <textarea name="content" rows="4" required class="w-full border p-2 rounded"></textarea>
    </div>

    <div>
      <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
      <input type="file" name="image" accept="image/*" class="w-full border p-2 rounded">
    </div>

    <div>
      <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
      <input type="date" name="published_at" required class="w-full border p-2 rounded">
    </div>

    <div class="mt-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Article Flags</label>
      <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded-lg border shadow-sm">
      <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_trending" value="1" class="form-checkbox text-indigo-600">
        <span class="text-gray-700">Trending</span>
      </label>

      <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_topic" value="1" class="form-checkbox text-indigo-600">
        <span class="text-gray-700">Topik</span>
      </label>

      <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_slider" value="1" class="form-checkbox text-indigo-600">
        <span class="text-gray-700">Featured Slider</span>
      </label>

      <label class="flex items-center space-x-2">
        <input type="checkbox" name="is_shorts" id="is_shorts" value="1" class="form-checkbox text-indigo-600">
        <span class="text-gray-700">Shorts</span>
      </label>

      <div id="videoInput" class="mt-4 hidden">
        <label for="video_path" class="block text-sm font-medium text-gray-700">Upload Video
        (Shorts)</label>
        <input type="file" name="video_path" id="video_path" accept="video/*"
        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
      </div>

      <div id="slider-images-section" class="hidden mt-4">
        <label for="slider_images" class="block text-sm font-medium text-gray-700 mb-1">
        Upload Slider Images / Videos
        </label>
        <input type="file" id="slider_images" name="slider_images[]" multiple accept="image/*,video/*" class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4
       file:rounded-lg file:border-0
       file:text-sm file:font-semibold
       file:bg-yellow-400 file:text-white
       hover:file:bg-yellow-500 transition">
      </div>
      </div>
    </div>

    <div class="flex justify-end space-x-2">
      <button type="button" onclick="document.getElementById('addArticleModal').classList.add('hidden')"
      class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
      <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black rounded">Save</button>
    </div>
    </form>
    </form>
  </div>

  <script>
    window.addEventListener('load', function () {
    // Toggle video input
    const checkbox = document.getElementById('is_shorts');
    const videoInput = document.getElementById('videoInput');

    if (checkbox) {
      checkbox.addEventListener('change', function () {
      videoInput.classList.toggle('hidden', !checkbox.checked);
      });
    }

    const sliderCheckbox = document.querySelector('input[name="is_slider"]');
    const topicCheckbox = document.querySelector('input[name="is_topic"]');
    const trendingCheckbox = document.querySelector('input[name="is_trending"]');
    const sliderImagesSection = document.getElementById('slider-images-section');

    function toggleSliderSection() {
      const showSlider =
      (sliderCheckbox && sliderCheckbox.checked) ||
      (topicCheckbox && topicCheckbox.checked) ||
      (trendingCheckbox && trendingCheckbox.checked);

      sliderImagesSection.classList.toggle('hidden', !showSlider);
    }

    if (sliderCheckbox && topicCheckbox && trendingCheckbox) {
      sliderCheckbox.addEventListener('change', toggleSliderSection);
      topicCheckbox.addEventListener('change', toggleSliderSection);
      trendingCheckbox.addEventListener('change', toggleSliderSection);

      toggleSliderSection();
    }

    // Initialize Swiper for each .mySwiper
    const swipers = document.querySelectorAll('.mySwiper');
    swipers.forEach(function (el) {
      new Swiper(el, {
      loop: true,
      navigation: {
        nextEl: el.querySelector('.swiper-button-next'),
        prevEl: el.querySelector('.swiper-button-prev'),
      },
      pagination: {
        el: el.querySelector('.swiper-pagination'),
        clickable: true,
      },
      });
    });
    });
  </script>
@endsection