@extends('tasks.layout')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-800">Task Details</h1>
        <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Back to Tasks
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $task->title }}</h2>
                <span class="{{ $task->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} px-3 py-1 rounded-full text-sm">
                    {{ $task->status }}
                </span>
            </div>
            
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-1">Due Date:</p>
                <p class="text-gray-800">{{ $task->due_date }}</p>
            </div>
            
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-1">Description:</p>
                <p class="text-gray-800 whitespace-pre-line">{{ $task->description }}</p>
            </div>
            
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-1">Created At:</p>
                <p class="text-gray-800">{{ $task->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
            
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-1">Last Updated:</p>
                <p class="text-gray-800">{{ $task->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
        
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-between">
            <a href="{{ route('tasks.edit', $task) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
                Edit Task
            </a>
            
            <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $task->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    Delete Task
                </button>
            </form>
        </div>
    </div>
@endsection