<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // jangan lupa import ini di atas

class ArticleController extends Controller
{
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

    private function loadCategoryPage($categoryName)
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

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }

// Untuk halaman daftar artikel
public function index()
{
    $articles = Article::latest()->get();
    $categories = Category::all();

    return view('news.article', compact('articles', 'categories')); // ⬅️ view list
}

// Untuk halaman edit satu artikel
public function edit($id)
{
    $article = Article::findOrFail($id); // hanya 1 artikel
    $categories = Category::all(); // kalau butuh dropdown kategori
    return view('news.edit', compact('article', 'categories')); // ⬅️ kirim 1 artikel
}


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $path = $request->file('image')->storeAs('uploads', $filename, 'public');
            $imagePath = $path; // otomatis 'uploads/namafile.jpg'
        }

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'published_at' => $request->published_at,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('article.index')->with('success', 'Article created successfully.');
    }


}