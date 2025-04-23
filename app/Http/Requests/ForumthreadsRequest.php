<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForumthreadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3',
            'category_id' => 'required|exists:forum_categories,id',
            'author_id' => 'required|numeric',
            'pinned' => 'boolean',
            'locked' => 'boolean',
            //'reply_count' => 'nullable|integer|min:0',
        ];
    }
}
