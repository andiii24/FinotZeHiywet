@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Add Resource</h2>
                        <p class="text-sm text-gray-500 mt-1">Resources added by administrators are published immediately.</p>
                    </div>
                    <a href="{{ route('spiritual-resources.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Library
                    </a>
                </div>


                <form action="{{ route('spiritual-resources.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    @include('spiritual_resources._form', ['submitLabel' => 'Add to Library'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
