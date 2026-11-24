<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'category', 'status', 'published_on', 'is_featured', 'body'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    /** Options for the "category" select box. */
    public const CATEGORIES = [
        'tutorial' => 'Tutorial',
        'news' => 'News',
        'tips' => 'Tips & tricks',
        'opinion' => 'Opinion',
    ];

    /** Options for the "status" radio buttons. */
    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_on' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * The user who wrote the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
