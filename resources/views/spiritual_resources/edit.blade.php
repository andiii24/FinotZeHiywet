@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Edit Resource</h2>
                    <a href="{{ route('spiritual-resources.show', $resource) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>


                <form action="{{ route('spiritual-resources.update', $resource) }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    @method('PUT')
                    @include('spiritual_resources._form', ['submitLabel' => 'Update Resource', 'resource' => $resource])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
