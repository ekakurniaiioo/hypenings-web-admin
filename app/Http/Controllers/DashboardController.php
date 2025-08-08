<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik singkat
        $totalNews = Article::where('is_shorts', 0)->count(); // Artikel biasa
        $totalShorts = Article::where('is_shorts', 1)->count(); // Shorts
        $totalUsers = User::count();
        $totalCategories = Category::count();

        // Categories with article counts (for the cards)
        $categories = Category::withCount('articles')->get();

        // Chart: articles published per day untuk 7 hari terakhir
        $days = collect();
        $articlesCountPerDay = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days->push($date->format('D')); // buat label: Mon, Tue, ...
            $count = Article::whereDate('created_at', $date)->count();
            $articlesCountPerDay->push($count);
        }

        $chartLabels = $days->toArray();       // e.g. ['Mon','Tue',...]
        $chartData = $articlesCountPerDay->toArray();

        // Recent Activity - dari Notification model (sesuaikan kolom)
        // Asumsi Notification punya: title, message, created_at
        $recentActivities = Notification::latest()->take(7)->get();

        // Top articles this month berdasarkan 'views'
        $startOfMonth = Carbon::now()->startOfMonth();
        $topArticles = Article::where('created_at', '>=', $startOfMonth)
            ->orderByDesc('views')
            ->take(7)
            ->get(['id', 'title', 'views', 'created_at']);

        // Optional: total views minggu ini (contoh agregat)
        $startOfWeek = Carbon::now()->startOfWeek();
        $viewsThisWeek = Article::where('updated_at', '>=', $startOfWeek)->sum('views');

        return view('dashboard', compact(
            'totalNews',
            'totalShorts',
            'totalUsers',
            'totalCategories',
            'categories',
            'chartLabels',
            'chartData',
            'recentActivities',
            'topArticles',
            'viewsThisWeek'
        ));
    }
}
