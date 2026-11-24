<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validation for both the "create" and "edit" post forms.
 */
class PostRequest extends FormRequest
{
    /**
     * Access is checked by PostPolicy in the controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'category' => ['required', Rule::in(array_keys(Post::CATEGORIES))],
            'status' => ['required', Rule::in(array_keys(Post::STATUSES))],
            'published_on' => ['nullable', 'date', 'required_if:status,published'],
            'is_featured' => ['nullable', 'boolean'],
            'body' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * Friendlier names in error messages ("published date" instead of "published on").
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'published_on' => 'published date',
        ];
    }

    /**
     * Validated data, with the checkbox turned into a real boolean
     * (an unticked checkbox is not sent by the browser at all).
     *
     * @return array<string, mixed>
     */
    public function postData(): array
    {
        return [
            ...$this->safe()->except('is_featured'),
            'is_featured' => $this->boolean('is_featured'),
        ];
    }
}
