<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|unique:posts,title,'. $this->title .',title',
            'content' => 'required',
            'images.*' => 'sometimes',
            'image_del.*' => 'sometimes',
            'tag.*' => 'sometimes',
            'tag_del.*' => 'sometimes'
        ];
    }
}
