<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'post_image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg'],
            'published_at' => ['nullable', 'date', 'after_or_equal:today'],
            //'user_id' => ['required', 'exists:users,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.exists' => 'The selected category is invalid.',
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'body.required' => 'The body field is required.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The selected status is invalid.',
            'post_image.image' => 'The file must be an image.',
            'post_image.max' => 'The image may not be greater than 2MB.',
            'post_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'published_at.date' => 'The published at is not a valid date.',
            'published_at.after_or_equal' => 'The published at must be a date after or equal to today.',
            //'user_id.required' => 'The user field is required.',
            //'user_id.exists' => 'The selected user is invalid.',
        ];
    }
}
