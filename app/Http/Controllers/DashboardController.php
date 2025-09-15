<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Article;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $author = Auth::user();
        $role = $author->role;

        // === GLOBAL DATA (buat superadmin & admin) ===
        $totalNews = Article::where('is_shorts', 0)->count();
        $totalShorts = Article::where('is_shorts', 1)->count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $users = User::all();
        $categories = Category::withCount('articles')->get();

        // === Users ===
        $latestArticles = Article::orderBy('published_at', 'desc')->orderBy('id', 'desc')->take(3)->get();
        $articlesCount = Article::where('is_shorts', 0)->count();
        $shortsCount = Article::where('is_shorts', 1)->count();
        $topicsCount = Category::count();
        $usersCount = User::count();

        // === Author-specific ===
        $authorId = $author->id;
        $myArticlesCount = Article::where('user_id', $authorId)->where('is_shorts', 0)->count();
        $myShortsCount = Article::where('user_id', $authorId)->where('is_shorts', 1)->count();
        $myRecentArticles = Article::where('user_id', $authorId)->latest()->take(5)->get();

        // Chart Author (artikel per hari)
        $dates = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i)->format('Y-m-d'));
        $articlesPerDay = $dates->map(fn($date) => Article::where('user_id', $authorId)->whereDate('created_at', $date)->count());
        $authorChartLabels = $dates->map(fn($d) => Carbon::parse($d)->format('M d'));
        $authorChartData = $articlesPerDay;

        // === Editor-specific (status aktif) ===
        $pendingArticles = Article::where('status', 'pending')->count();
        $approvedArticles = Article::where('status', 'approved')->count();
        $rejectedArticles = Article::where('status', 'rejected')->count();
        $totalArticles = Article::count();

        // List artikel pending terbaru
        $pendingList = Article::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        // Chart Editor (artikel yang direview per hari)
        $editorDates = collect(range(6, 0))->map(fn($i) => Carbon::today()->subDays($i)->format('Y-m-d'));
        $editorArticlesPerDay = $editorDates->map(
            fn($date) =>
            Article::where('status', 'pending')->whereDate('updated_at', $date)->count()
        );
        $editorChartLabels = $editorDates->map(fn($d) => Carbon::parse($d)->format('M d'));
        $editorChartData = $editorArticlesPerDay;

        // === Global Chart (admin & superadmin) ===
        $days = collect();
        $articlesCountPerDay = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days->push($date->format('D'));
            $articlesCountPerDay->push(Article::whereDate('created_at', $date)->count());
        }
        $chartLabels = $days->toArray();
        $chartData = $articlesCountPerDay->toArray();

        // Recent Activity & Top Articles
        $recentActivities = Notification::latest()->take(7)->get();
        $topArticles = Article::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->orderByDesc('views')->take(15)->get(['id', 'title', 'views', 'created_at']);
        $viewsThisWeek = Article::where('updated_at', '>=', Carbon::now()->startOfWeek())->sum('views');

        // === Tentukan view sesuai role ===
        switch ($role) {
            case 'superadmin':
                $view = 'dashboard.superadmin';
                break;
            case 'admin':
                $view = 'dashboard.admin';
                break;
            case 'editor':
                $view = 'dashboard.editor';
                break;
            case 'author':
                $view = 'dashboard.author';
                break;
            default:
                $view = 'dashboard.user';
                break;
        }

        return view($view, compact(
            // Global
            'totalNews',
            'totalShorts',
            'totalUsers',
            'totalCategories',
            'categories',
            'users',
            'chartLabels',
            'chartData',
            'recentActivities',
            'topArticles',
            'viewsThisWeek',

            // Users
            'latestArticles',
            'articlesCount',
            'shortsCount',
            'topicsCount',
            'usersCount',

            // Author
            'author',
            'myArticlesCount',
            'myShortsCount',
            'myRecentArticles',
            'authorChartLabels',
            'authorChartData',

            // Editor (status aktif)
            'pendingArticles',
            'approvedArticles',
            'rejectedArticles',
            'totalArticles',
            'pendingList',
            'editorChartLabels',
            'editorChartData'
        ));
    }
}
