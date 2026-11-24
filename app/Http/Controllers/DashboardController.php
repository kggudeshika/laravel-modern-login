<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * A single-action controller: Laravel calls __invoke() for the /dashboard route.
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'stats' => [
                'total' => $user->posts()->count(),
                'published' => $user->posts()->where('status', 'published')->count(),
                'drafts' => $user->posts()->where('status', 'draft')->count(),
                'featured' => $user->posts()->where('is_featured', true)->count(),
            ],
            'recent' => $user->posts()->latest()->take(5)->get(),
            'categories' => Post::CATEGORIES,
        ]);
    }
}
