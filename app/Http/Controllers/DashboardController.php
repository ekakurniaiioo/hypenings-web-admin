<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ambil semua kategori dengan jumlah artikelnya
        $categories = Category::withCount('articles')->get();

        return view('dashboard', compact('categories'));
    }
}
