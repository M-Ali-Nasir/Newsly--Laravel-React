<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use jcobhams\NewsApi\NewsApi;
use App\Services\NewsService;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{

    protected $newsService;
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    //fetching news and saving in database
    public function fetchNews()
    {
        $this->newsService->fetchAndStoreNews();
    }


    //View Home Page
    public function home()
    {
        $this->fetchNews();
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    //View Guest News

    public function guestTopNews(Request $request)
    {
        $q = $request->input('search', '');
        $page_size = 10;
        $page = $request->input('page', 1);

        $query = Article::query();


        if (!empty($q)) {
            $query->where('title', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%')
                ->orWhere('content', 'like', '%' . $q . '%');
        }


        $totalResults = $query->count();
        $articles = $query->orderBy('published_at', 'desc')
            ->skip(($page - 1) * $page_size)
            ->take($page_size)
            ->get();


        $totalPages = ceil($totalResults / $page_size);


        return Inertia::render('GuestNews', [
            'all_articles' => $articles,
            'initialFilters' => $request->all(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    //Guest Sports News
    public function guestSportsNews(Request $request)
    {

        $q = $request->input('search', '');
        $page_size = 10;
        $page = $request->input('page', 1);
        $category = 'Sports';


        $query = Article::query()->whereHas('category', function ($query) use ($category) {
            $query->where('name', $category);
        });


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


        return Inertia::render('GuestNews', [
            'all_articles' => $articles,
            'initialFilters' => $request->all(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    //Guest Entertainment News

    public function guestEntertainmentNews(Request $request)
    {

        $q = $request->input('search', '');
        $page_size = 10;
        $page = $request->input('page', 1);
        $category = 'Entertainment';


        $query = Article::query()->whereHas('category', function ($query) use ($category) {
            $query->where('name', $category);
        });

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


        return Inertia::render('GuestNews', [
            'all_articles' => $articles,
            'initialFilters' => $request->all(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
