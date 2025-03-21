<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|min:10',
            'due_date' => 'required|date|after:today',
            'status' => 'sometimes|in:Pending,Completed',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The task title is required.',
            'description.min' => 'The description must be at least 10 characters.',
            'due_date.after' => 'The due date must be a future date.',
        ];
    }
}