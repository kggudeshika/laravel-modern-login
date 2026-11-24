<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\PostRequest;

/**
 * Same rules as the normal post form, plus choosing the author.
 * Access is already limited by the "admin" middleware.
 */
class AdminPostRequest extends PostRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'user_id' => 'author',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function postData(): array
    {
        return [
            ...parent::postData(),
            'user_id' => (int) $this->input('user_id'),
        ];
    }
}
