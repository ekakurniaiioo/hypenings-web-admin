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

        // Subfolder logika
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

        // Upload video utama jika ada
        if ($request->hasFile('video_path')) {
            $video = $request->file('video_path');
            $videoName = time() . '_' . Str::slug(pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $video->getClientOriginalExtension();
            $video->move($destination, $videoName);
            $validated['video_path'] = 'uploads/' . $subfolder . $videoName;
        }

        $validated['user_id'] = Auth::id(); // pastikan ada user_id kalau pakai Auth
        $article = Article::create($validated);

        // Notifikasi
        if (Auth::check()) {
            Notification::create([
                'title' => 'Berita Baru Ditambahkan',
                'message' => Auth::user()->name . ' menambahkan berita: ' . $validated['title'],
                'created_by' => Auth::id(),
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

    // Mulai query dari awal
    $articlesQuery = Article::query();

    // Filter kategori
    if ($request->filled('category')) {
        $articlesQuery->where('category_id', $request->category);
    }

    // Filter pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $articlesQuery->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    // Urutkan dari tanggal paling awal
    $articles = $articlesQuery
        ->orderBy('published_at', 'desc')
        ->paginate(150);

    return view('article', compact('articles', 'categories'));
}


    public function show($id)
    {
        $article = Article::findOrFail($id);

        $sessionKey = 'article_viewed_' . $article->id;

        if (!session()->has($sessionKey)) {
            $article->increment('views');
            session()->put($sessionKey, true);
        }

        return view('articles.show', compact('article'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();

        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $article = Article::findOrFail($id);
        $article->update($request->only(['title', 'content']));

        return redirect()->route('articles.index', $article->id)
            ->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus.');
    }


}
