<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForumCategoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
    return [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'accepts_threads' => 'boolean',
        'is_private' => 'boolean',
        'parent_id' => 'nullable|integer|exists:forum_categories,id',
        'color_light_mode' => 'nullable|string|max:255',
        'color_dark_mode' => 'nullable|string|max:255',
        
    ];
}
}
