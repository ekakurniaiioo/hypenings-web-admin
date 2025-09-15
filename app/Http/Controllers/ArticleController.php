<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\SliderMedia;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{

    public function dashboard()
    {
        $categories = Category::all();

        return view('dashboard', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Article::class); // cek policy dulu

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required',
            'image' => 'required|image',
            'published_at' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'is_trending' => 'nullable|boolean',
            'is_topic' => 'nullable|boolean',
            'is_featured_slider' => 'nullable|boolean',
            'is_shorts' => 'nullable|boolean',
            'slider_images.*' => 'nullable|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            'video_path' => 'nullable|mimes:mp4,mov|max:51200',
        ]);

        $validated['slug'] = $request->input('slug') ?? Str::slug($request->title);

        // published_at
        if ($request->filled('published_at')) {
            $validated['published_at'] = \Carbon\Carbon::parse($request->published_at)
                ->setTimeFromTimeString(now()->format('H:i:s'));
        } else {
            $validated['published_at'] = now();
        }

        // Tentukan subfolder
        $hasSlider = $request->hasFile('slider_images');
        if ($request->boolean('is_shorts')) {
            $subfolder = 'shorts/';
        } elseif (($request->boolean('is_trending') || $request->boolean('is_topic') || $request->boolean('is_featured_slider')) && $hasSlider) {
            $subfolder = 'articleWithSlider/';
        } else {
            $subfolder = 'articleWithoutSlider/';
        }

        $destination = public_path('storage/uploads/' . $subfolder);
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        // Upload image utama
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $image->move($destination, $imageName);
            $validated['image'] = 'uploads/' . $subfolder . $imageName;
        }

        // Upload video utama
        if ($request->hasFile('video_path')) {
            $video = $request->file('video_path');
            $videoName = time() . '_' . Str::slug(pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $video->getClientOriginalExtension();
            $video->move($destination, $videoName);
            $validated['video_path'] = 'uploads/' . $subfolder . $videoName;
        }

        // Simpan artikel
        $validated['user_id'] = Auth::id();
        $article = Article::create($validated);

        // Notifikasi
        if (Auth::check()) {
            Notification::create([
                'title' => 'News item added',
                'message' => Auth::user()->name . ' added news: ' . $validated['title'],
                'created_by' => Auth::id(),
                'is_read' => false,
            ]);
        }

        // Simpan slider jika ada
        if ($hasSlider) {
            $slider = Slider::create(['article_id' => $article->id]);

            foreach ($request->file('slider_images') as $index => $file) {
                try {
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    $file->move($destination, $fileName);

                    SliderMedia::create([
                        'slider_id' => $slider->id,
                        'file_path' => 'uploads/' . $subfolder . $fileName,
                        'media_type' => $file->getClientMimeType(),
                        'order' => $index,
                    ]);
                } catch (\Exception $e) {
                    Log::error("Slider media gagal disimpan: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil disimpan.');
    }

    public function slider()
    {
        return $this->hasOne(Slider::class);
    }

    private function loadCategoryPage(string $categoryName)
    {
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        $posts = Article::where('category_id', $category->id)
            ->latest()
            ->paginate(6);

        return view("categories.$categoryName", compact('posts'));
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $articlesQuery = Article::with(['user', 'slider.media']);

        $articlesQuery = Article::query();

        if ($request->filled('category')) {
            $articlesQuery->where('category_id', $request->category);
        }

        if ($request->has('is_shorts')) {
            $articlesQuery->where('is_shorts', $request->is_shorts);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $articlesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $articlesQuery
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(150);

        return view('article', compact(
            'articles',
            'categories',
        ));
    }

    public function edit($id)
    {
        $article = Article::with('slider.sliderMedia')->findOrFail($id);

        $categories = Category::all();

        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image',
            'published_at' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'is_trending' => 'nullable|boolean',
            'is_topic' => 'nullable|boolean',
            'is_featured_slider' => 'nullable|boolean',
            'is_shorts' => 'nullable|boolean',
            'slider_images.*' => 'nullable|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            'video_path' => 'nullable|mimes:mp4,mov|max:51200',
        ]);

        $validated['slug'] = $request->input('slug') ?? Str::slug($request->title);

        // published_at
        if ($request->filled('published_at')) {
            $validated['published_at'] = \Carbon\Carbon::parse($request->published_at)
                ->setTimeFromTimeString(now()->format('H:i:s'));
        } else {
            $validated['published_at'] = now();
        }

        // cek subfolder
        $hasSlider = $request->hasFile('slider_images');
        if ($request->boolean('is_shorts')) {
            $subfolder = 'shorts/';
        } elseif (($request->boolean('is_trending') || $request->boolean('is_topic') || $request->boolean('is_featured_slider')) && $hasSlider) {
            $subfolder = 'articleWithSlider/';
        } else {
            $subfolder = 'articleWithoutSlider/';
        }

        $destination = public_path('storage/uploads/' . $subfolder);
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        // update image utama
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $image->move($destination, $imageName);

            // hapus lama kalau ada
            if ($article->image && file_exists(public_path('storage/' . $article->image))) {
                unlink(public_path('storage/' . $article->image));
            }

            $validated['image'] = 'uploads/' . $subfolder . $imageName;
        }

        // update video utama
        if ($request->hasFile('video_path')) {
            $video = $request->file('video_path');
            $videoName = time() . '_' . Str::slug(pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $video->getClientOriginalExtension();
            $video->move($destination, $videoName);

            // hapus lama kalau ada
            if ($article->video_path && file_exists(public_path('storage/' . $article->video_path))) {
                unlink(public_path('storage/' . $article->video_path));
            }

            $validated['video_path'] = 'uploads/' . $subfolder . $videoName;
        }

        // update article utama
        $article->update($validated);

        // update / ganti slider media
        if ($hasSlider) {
            $slider = $article->slider ?? $article->slider()->create([]);

            // Hapus semua media lama
            foreach ($slider->sliderMedia as $oldMedia) {
                if ($oldMedia->file_path && file_exists(public_path('storage/' . $oldMedia->file_path))) {
                    unlink(public_path('storage/' . $oldMedia->file_path));
                }
                $oldMedia->delete();
            }

            // Insert media baru
            foreach ($request->file('slider_images', []) as $index => $file) {
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $fileName);

                $slider->sliderMedia()->create([
                    'file_path' => 'uploads/' . $subfolder . $fileName,
                    'media_type' => $file->getClientMimeType(),
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $title = $article->title;
        $article->delete();

        if (Auth::check()) {
            Notification::create([
                'title' => 'News item deleted',
                'message' => Auth::user()->name . ' deleted news: ' . $title,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function review(Article $article)
    {
        $this->authorize('review-articles'); // cek sesuai gate
        return view('articles.review', compact('article'));
    }

    public function updateStatus(Request $request, Article $article)
    {
        $this->authorize('review-articles'); // cek sesuai gate

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string'
        ]);

        $article->status = $request->status;
        $article->review_notes = $request->notes ?? null;
        $article->save();

        return redirect()->route('dashboard')->with('success', 'Artikel berhasil diupdate!');
    }

}
