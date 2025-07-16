<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home(): View
    {
        $posts = Post::query()
            ->where('active', '=', 1)
            ->whereDate('published_at', '<', Carbon::now())
            ->orderBy('published_at', 'desc')
            ->paginate(5);
        // latest posts:
        // $latest_post = Post::where('active', '= ', 1)
        //     ->where('published_at', '<', Carbon::now())
        //     ->orderBy('published_at', 'desc')
        //     ->limit(1)
        //     ->first();
        // // top 3 popular posts based on upvotes
        // $popular_posts = Post::query()
        //     ->leftJoin('upvote_down_votes', 'posts.id', '=', 'upvote_down_votes.post_id')
        //     ->select('posts.*', DB::raw('COUNT(upvote_down_votes.id) as upvote_count'))
        //     ->where(function ($query) {
        //         $query->whereNull('upvote_down_votes.is_upvote')->where('upvote_down_votes.is_upvote', '=', 1);
        //     })
        //     ->where('active', '= ', 1)
        //     ->where('published_at', '<', Carbon::now())
        //     ->orderBy('upvote_count', 'desc')
        //     ->groupBy('posts.id')
        //     ->limit(3)
        //     ->get();

        // not authorized: posts based on views
        // show recent categories with their latest posts
        // return view('home', compact('latest_post', 'popular_posts'));
        return view('home', compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Request $request)
    {
        if (! $post->active || $post->published_at > Carbon::now()) {
            throw new NotFoundHttpException;
        }
        $next = Post::query()
            ->where('active', '=', 1)
            ->whereDate('published_at', '<=', Carbon::now())
            ->whereDate('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->limit(1)
            ->first();

        $prev = Post::query()
            ->where('active', '=', 1)
            ->whereDate('published_at', '<=', Carbon::now())
            ->whereDate('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->limit(1)
            ->first();

        $user = $request->user();

        PostView::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'post_id' => $post->id,
            'user_id' => $user?->id,
        ]);

        return view('post.view', compact('post', 'prev', 'next'));

    }

    public function byCategory(Category $category)
    {
        $posts = Post::query()
            ->join('category_post', 'posts.id', '=', 'category_post.post_id')
            ->where('category_post.category_id', '=', $category->id)
            ->where('active', '=', true)
            ->whereDate('published_at', '<=', Carbon::now())
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('home', compact('posts'));
    }
}
