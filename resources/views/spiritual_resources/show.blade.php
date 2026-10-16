@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $resource->title }}</h2>
                    <div class="flex flex-wrap gap-2">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('spiritual-resources.edit', $resource) }}" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition">Edit</a>
                        @endif
                        <a href="{{ route('spiritual-resources.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Library</a>
                    </div>
                </div>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex flex-wrap justify-between items-center gap-3">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Resource Details</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this spiritual resource.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $resource->type_label }}
                            </span>
                            @if($resource->isPending())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Pending Review</span>
                            @elseif($resource->isRejected())
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @endif
                        </div>
                    </div>

                    @if($resource->cover_image)
                        <div class="px-4 sm:px-6 pb-4">
                            <img src="{{ asset('storage/' . $resource->cover_image) }}" alt="{{ $resource->title }}" class="max-h-64 rounded-md object-contain">
                        </div>
                    @endif

                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Title</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $resource->title }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $resource->type_label }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Categories</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($resource->categories as $cat)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">{{ $cat->name }}</span>
                                        @empty
                                            <span class="text-gray-400">None</span>
                                        @endforelse
                                    </div>
                                </dd>
                            </div>
                            @if($resource->author)
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Author</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $resource->author }}</dd>
                                </div>
                            @endif
                            @if($resource->source)
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $resource->source }}</dd>
                                </div>
                            @endif
                            @if($resource->url)
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">URL</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline break-all">
                                            {{ $resource->url }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                            @if($resource->file_path)
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">File</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-primary text-white rounded-md hover:opacity-90 transition text-sm">
                                            Download / Open PDF
                                        </a>
                                    </dd>
                                </div>
                            @endif
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Posted By</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $resource->submitter?->name ?? 'Unknown' }}
                                    <span class="text-gray-500"> on {{ $resource->created_at->format('F d, Y') }}</span>
                                </dd>
                            </div>
                            @if($resource->isApproved())
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $resource->reviewer?->name ?? 'Unknown' }}
                                        @if($resource->reviewed_at)
                                            <span class="text-gray-500"> on {{ $resource->reviewed_at->format('F d, Y') }}</span>
                                        @endif
                                    </dd>
                                </div>
                            @elseif($resource->isRejected())
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Rejected By</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $resource->reviewer?->name ?? 'Unknown' }}
                                        @if($resource->reviewed_at)
                                            <span class="text-gray-500"> on {{ $resource->reviewed_at->format('F d, Y') }}</span>
                                        @endif
                                    </dd>
                                </div>
                            @elseif($resource->isPending())
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Review Status</dt>
                                    <dd class="mt-1 text-sm text-amber-700 sm:mt-0 sm:col-span-2">Awaiting admin approval</dd>
                                </div>
                            @endif
                            @if($resource->isRejected() && $resource->rejection_reason)
                                <div class="bg-red-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-red-600">Rejection Reason</dt>
                                    <dd class="mt-1 text-sm text-red-800 sm:mt-0 sm:col-span-2">{{ $resource->rejection_reason }}</dd>
                                </div>
                            @endif
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                                    {{ $resource->description ?? 'No description provided.' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($resource->url && $resource->type === 'youtube')
                    @php
                        $youtubeId = null;
                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $resource->url, $matches)) {
                            $youtubeId = $matches[1];
                        }
                    @endphp
                    @if($youtubeId)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Video Preview</h3>
                            <div class="aspect-video w-full max-w-3xl">
                                <iframe class="w-full h-full rounded-lg" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="{{ $resource->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif
                @endif

                @if(Auth::user()->isAdmin() && $resource->isPending())
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Review Submission</h3>
                        <div class="flex flex-wrap gap-3">
                            <button type="button"
                                    onclick="approveResource({{ $resource->id }}, @js($resource->title))"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                Approve
                            </button>
                            <button type="button"
                                    onclick="rejectResource({{ $resource->id }}, @js($resource->title))"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                Reject
                            </button>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->isAdmin())
                    <div class="mt-6">
                        <button type="button"
                                onclick="deleteResource({{ $resource->id }}, @js($resource->title))"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Delete Resource
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isAdmin())
<script>
function approveResource(resourceId, title) {
    AppSwal.confirm({
        title: 'Approve Resource?',
        html: `<div class="text-left"><p><strong>Title:</strong> ${title}</p><p class="text-green-600 font-semibold mt-2">This will add the resource to the library.</p></div>`,
        icon: 'question',
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Yes, Approve',
    }).then((result) => {
        if (result.isConfirmed) {
            AppSwal.post(`/spiritual-resources/${resourceId}/review`, { action: 'approve' });
        }
    });
}

function rejectResource(resourceId, title) {
    AppSwal.confirm({
        title: 'Reject Resource?',
        html: `<div class="text-left mb-3"><p><strong>Title:</strong> ${title}</p><p class="text-red-600 font-semibold mt-2">This suggestion will not appear in the library.</p></div>`,
        icon: 'warning',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Reject',
        input: 'textarea',
        inputLabel: 'Rejection Reason',
        inputPlaceholder: 'Explain why this submission is being rejected...',
        inputAttributes: { 'aria-label': 'Rejection reason' },
        inputValidator: (value) => {
            if (!value || !value.trim()) {
                return 'Please provide a rejection reason.';
            }
        },
    }).then((result) => {
        if (result.isConfirmed) {
            AppSwal.post(`/spiritual-resources/${resourceId}/review`, {
                action: 'reject',
                rejection_reason: result.value,
            });
        }
    });
}

function deleteResource(resourceId, title) {
    AppSwal.confirm({
        title: 'Delete Resource?',
        html: `<div class="text-left"><p><strong>Title:</strong> ${title}</p><p class="text-red-600 font-semibold mt-2">This action cannot be undone!</p></div>`,
        icon: 'error',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete Resource',
    }).then((result) => {
        if (result.isConfirmed) {
            AppSwal.post(`/spiritual-resources/${resourceId}`, { _method: 'DELETE' });
        }
    });
}
</script>
@endif
@endsection
