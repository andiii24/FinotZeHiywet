@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $job->name }}</h2>
                    <div class="space-x-2">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('jobs.edit', $job->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Edit Job</a>
                        @endif
                        <a href="{{ route('jobs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Jobs</a>
                    </div>
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Job Details</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this job posting.</p>
                        </div>
                        <div>
                            @if($job->status == 'open')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Open
                                </span>
                            @elseif($job->status == 'closed')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Closed
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a href="{{ route('job-categories.show', $job->category->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $job->category->name }}
                                    </a>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Number of Vacancies</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->vacancy }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Posted By</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->creator->name }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Posted On</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->created_at->format('F d, Y') }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $job->updated_at->format('F d, Y H:i:s') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="prose max-w-none">
                                        {{ $job->description ?? 'No description provided' }}
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <div class="mt-6 flex justify-between">
                        <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                                    onclick="return confirm('Are you sure you want to delete this job?')">
                                Delete Job
                            </button>
                        </form>

                        @if($job->status != 'open')
                            <form action="{{ route('jobs.update', $job->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $job->name }}">
                                <input type="hidden" name="job_category_id" value="{{ $job->job_category_id }}">
                                <input type="hidden" name="vacancy" value="{{ $job->vacancy }}">
                                <input type="hidden" name="description" value="{{ $job->description }}">
                                <input type="hidden" name="status" value="open">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                    Publish Job
                                </button>
                            </form>
                        @elseif($job->status == 'open')
                            <form action="{{ route('jobs.update', $job->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $job->name }}">
                                <input type="hidden" name="job_category_id" value="{{ $job->job_category_id }}">
                                <input type="hidden" name="vacancy" value="{{ $job->vacancy }}">
                                <input type="hidden" name="description" value="{{ $job->description }}">
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    Close Job
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
