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
                    <a href="{{ route('spiritual-resources.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Library</a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('spiritual-resources.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    @include('spiritual_resources._form', ['submitLabel' => 'Add to Library'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
