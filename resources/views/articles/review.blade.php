@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl text-center font-bold mb-6">📝 Review Artikel</h1>

    <div class="flex justify-center">
        <div class="bg-white max-w-4xl w-full p-10 rounded-3xl shadow-xl">
            {{-- Judul --}}
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-2">{{ $article->title }}</h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                By <span class="font-medium text-gray-700">{{ $article->user->name }}</span>
                • {{ $article->created_at->format('d M Y') }}
            </p>

            {{-- Media --}}
            <div class="flex justify-center gap-6 mb-8">
                @if($article->image && (!$article->slider || !$article->slider->slidermedia->count()))
                    <div class="w-full md:w-[420px] h-[560px] rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $article->image) }}"
                            class="w-full h-full object-cover hover:scale-105 transition duration-500">
                    </div>
                @endif

                @if($article->video_path && (!$article->slider || !$article->slider->slidermedia->count()))
                    <div class="w-full md:w-[420px] h-[560px] rounded-2xl overflow-hidden shadow-lg">
                        <video controls autoplay loop muted playsinline
                            class="w-full h-full object-cover rounded-2xl hover:scale-105 transition duration-500">
                            <source src="{{ asset('storage/' . $article->video_path) }}" type="video/mp4">
                        </video>
                    </div>
                @endif

                {{-- Slider --}}
                @if ($article->slider && $article->slider->slidermedia->count())
                    <div class="w-full md:w-[420px] h-[560px] rounded-2xl overflow-hidden shadow-lg">
                        <div class="swiper mySwiper h-full w-full">
                            <div class="swiper-wrapper">
                                @foreach ($article->slider->slidermedia as $media)
                                    <div class="swiper-slide">
                                        @if(Str::endsWith($media->file_path, ['.mp4', '.mov']))
                                            <video controls class="w-full h-full object-cover rounded-2xl">
                                                <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/' . $media->file_path) }}"
                                                class="w-full h-full object-cover rounded-2xl">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next text-gray-700"></div>
                            <div class="swiper-button-prev text-gray-700"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Konten --}}
            <div class="prose prose-lg max-w-none border-b py-8 text-gray-800 leading-relaxed mb-6">
                {!! $article->content !!}
            </div>

            {{-- Status Info --}}
            <div class="flex flex-col items-center gap-3 mb-10">
                <p class="text-gray-400">Status</p>
                <span
                    class="px-4 py-1.5 rounded-full text-white font-semibold text-sm 
                                        {{ $article->status == 'approved' ? 'bg-green-500' : ($article->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                    {{ ucfirst($article->status) }}
                </span>
                @if($article->review_notes)
                    <span class="text-gray-500 italic text-center">
                        Catatan: {{ $article->review_notes }}
                    </span>
                @endif
            </div>

            {{-- Form Review --}}
            <form action="{{ route('articles.updateStatus', $article->id) }}" method="POST"
                class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 rounded-2xl shadow-inner border border-gray-200">
                @csrf

                {{-- Header Form --}}
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-3-3v6m-7 8h14a2 2 0 002-2V6a2 2 0 00-2-2h-4l-2-2h-4l-2 2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-bold text-gray-800">Update Status</h2>
                </div>

                {{-- Status --}}
                <div class="mb-5">
                    <label for="status" class="block text-gray-700 font-semibold mb-2">Status</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                        <option value="pending" @if($article->status == 'pending') selected @endif>Pending</option>
                        <option value="approved" @if($article->status == 'approved') selected @endif>Approved</option>
                        <option value="rejected" @if($article->status == 'rejected') selected @endif>Rejected</option>
                    </select>
                </div>

                {{-- Notes --}}
                <div class="mb-6">
                    <label for="notes" class="block text-gray-700 font-semibold mb-2">Catatan / Feedback</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">{{ $article->review_notes ?? '' }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2 rounded-lg bg-gray-200 text-gray-800 font-medium hover:bg-gray-300 transition">Kembali</a>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md transition">
                        Simpan Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
</script>
@endsection
