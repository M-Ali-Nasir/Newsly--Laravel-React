<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\UserPreference;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use jcobhams\NewsApi\NewsApi;


class DashboardController extends Controller
{
    //
    public function viewDashboard(Request $request)
    {
        $q = $request->input('search', '');
        $page_size = 10;
        $page = $request->input('page', 1);
        $categoryId = $request->input('categoryId', 0);


        $categories = Category::all();
        $preferences = UserPreference::where('user_id', Auth::id())->select('id', 'cat_id')->get();
        $query = Article::query();


        if (!empty($q)) {
            $query->where('title', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%')
                ->orWhere('content', 'like', '%' . $q . '%');
        }

        if ($categoryId !== 0) {
            $query->where('category_id', $categoryId);
        }


        $totalResults = $query->count();
        $articles = $query->orderBy('published_at', 'desc')
            ->skip(($page - 1) * $page_size)
            ->take($page_size)
            ->get();


        $totalPages = ceil($totalResults / $page_size);



        return Inertia::render('Dashboard', [
            'all_articles' => $articles,
            'initialFilters' => $request->all(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'preferences' => $preferences,
        ]);
    }

    public function getPreferedNews(Request $request)
    {
        $userId = Auth::id();

        $preferredCategoryIds = DB::table('user_preferences')
            ->where('user_id', $userId)
            ->pluck('cat_id');

        $categories = Category::all();
        $preferences = UserPreference::where('user_id', Auth::id())->select('id', 'cat_id')->get();

        if ($preferredCategoryIds->isEmpty()) {
            return Inertia::render('Dashboard', [
                'all_articles' => [],
                'initialFilters' => $request->all(),
                'currentPage' => 1,
                'totalPages' => 0,
                'categories' => $categories,
                'preferences' => $preferences,
            ]);
        }


        $q = $request->input('search', '');
        $page_size = 10;
        $page = $request->input('page', 1);


        $query = Article::whereIn('category_id', $preferredCategoryIds);


        if (!empty($q)) {
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('title', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhere('content', 'like', '%' . $q . '%');
            });
        }

        $totalResults = $query->count();
        $articles = $query->orderBy('published_at', 'desc')
            ->skip(($page - 1) * $page_size)
            ->take($page_size)
            ->get();


        $totalPages = ceil($totalResults / $page_size);



        return Inertia::render('Dashboard', [
            'all_articles' => $articles,
            'initialFilters' => $request->all(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'preferences' => $preferences,
        ]);
    }
}
