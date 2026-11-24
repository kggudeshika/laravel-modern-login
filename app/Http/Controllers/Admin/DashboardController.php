<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * GET /admin — overview numbers and the latest activity.
     */
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'admins' => User::where('is_admin', true)->count(),
                'unverified' => User::whereNull('email_verified_at')->count(),
                'posts' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'drafts' => Post::where('status', 'draft')->count(),
            ],
            'latestUsers' => User::latest()->take(5)->get(),
            'latestPosts' => Post::with('user')->latest()->take(5)->get(),
        ]);
    }
}
