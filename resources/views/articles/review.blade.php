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