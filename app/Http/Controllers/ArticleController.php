<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Slider;
use App\Models\SliderMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{


public function store(Request $request)
{
    Log::info("MASUK STORE", $request->all());

    $data = $request->validate([
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
    ]);

    // Generate slug kalau belum dikasih
    $data['slug'] = $request->input('slug') ?? Str::slug($request->title);

    // Simpan image utama
    if ($request->hasFile('image')) {
        $filename = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('uploads', $filename);
        $data['image'] = 'uploads/' . $filename;
    }

    // Simpan artikel utama
    $article = Article::create($data);

    // Proses slider jika ada
    if ($request->hasFile('slider_images')) {
        foreach ($request->file('slider_images') as $sliderImage) {
            try {
                $sliderFilename = time() . '_' . $sliderImage->getClientOriginalName();
                $sliderImage->storeAs('uploads', $sliderFilename);

                Slider::create([
                    'article_id' => $article->id,
                    'media_path' => 'uploads/' . $sliderFilename,
                    'type' => $sliderImage->getClientMimeType(),
                ]);
            } catch (\Exception $e) {
                Log::error("Gagal simpan slider: " . $e->getMessage());
                // Boleh juga kasih flash message kalau mau: session()->flash('warning', 'Beberapa slider gagal disimpan.');
            }
        }
    }

    return redirect()->route('article.index')->with('success', 'Artikel berhasil disimpan.');
}

    public function lifestyle()
    {
        return $this->loadCategoryPage('lifestyle');
    }

    public function music()
    {
        return $this->loadCategoryPage('music');
    }

    public function sport()
    {
        return $this->loadCategoryPage('sport');
    }

    public function knowledge()
    {
        return $this->loadCategoryPage('knowledge');
    }

    public function other()
    {
        return $this->loadCategoryPage('other');
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

        $articlesQuery = Article::orderBy('id', 'asc');

        if ($request->has('category')) {
            $articlesQuery->where('category_id', $request->category);
        }

        $articles = $articlesQuery->get();

        return view('news.article', compact('articles', 'categories'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();

        return view('news.edit', compact('article', 'categories'));
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.show', compact('article'));
    }
}
