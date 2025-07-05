<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function lifestyle(Request $request)
    {
        return $this->loadCategory('lifestyle', $request);
    }

    public function music(Request $request)
    {
        return $this->loadCategory('music', $request);
    }

    public function sport(Request $request)
    {
        return $this->loadCategory('sport', $request);
    }

    public function knowledge(Request $request)
    {
        return $this->loadCategory('knowledge', $request);
    }

    public function other(Request $request)
    {
        return $this->loadCategory('other', $request);
    }

    private function loadCategory($categoryName, $request)
    {
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        $posts = Article::where('category_id', $category->id)
                        ->latest()
                        ->paginate(3);

        if ($request->ajax()) {
            return view('components.article-list', compact('posts'))->render();
        }

        return view("categories.$categoryName", compact('posts'));
    }
    
}
