<?php

namespace App\View\Components;

use App\Models\Category;
use DB;
use Illuminate\View\Component;
use Illuminate\View\View;
use Mockery\Matcher\Closure;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function __construct(public ?string $meta_title = null, public ?string $meta_description = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = Category::query()
            ->join('category_post', 'categories.id', '=', 'category_post.category_id')
            ->select('categories.title', 'categories.slug', DB::raw('count(*) as total'))
            ->groupBy('categories.id', 'categories.title', 'categories.slug')
            ->orderByDesc('total')
            ->get();

        return view('layouts.app', compact('categories'));
    }
}
