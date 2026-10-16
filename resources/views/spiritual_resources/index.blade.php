@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Spiritual Resource Library</h2>
                        <p class="text-sm text-gray-500 mt-1">Discover, access, and share spiritual and educational resources.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('spiritual-resources.my-submissions') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 transition">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            My Submissions
                        </a>
                        <a href="{{ route('spiritual-resources.suggest') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-sm font-medium text-white shadow-sm hover:opacity-90 hover:shadow-md transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Suggest a Resource
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('spiritual-resources.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-secondary text-sm font-semibold text-primary shadow-sm hover:brightness-95 hover:shadow-md transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Add Resource
                            </a>
                            @if($pendingCount > 0)
                                <a href="{{ route('spiritual-resources.index', ['status' => 'pending']) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-amber-50 text-sm font-medium text-amber-800 border border-amber-200 shadow-sm hover:bg-amber-100 transition">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pending Reviews
                                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full bg-amber-500 text-white text-xs font-bold">{{ $pendingCount }}</span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>


                <div class="mb-6 bg-gray-50 p-4 rounded-md">
                    <form action="{{ route('spiritual-resources.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   placeholder="Title, keywords, author..."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="flex-1 min-w-[180px]">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[160px]">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Resource Type</label>
                            <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">All Types</option>
                                @foreach(\App\Models\SpiritualResource::TYPES as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(Auth::user()->isAdmin())
                            <div class="flex-1 min-w-[160px]">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="">Approved (Library)</option>
                                    @foreach(\App\Models\SpiritualResource::STATUSES as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-sm font-medium text-white shadow-sm hover:opacity-90 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('spiritual-resources.index') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($resources as $resource)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition border border-gray-100">
                            @if($resource->cover_image)
                                <div class="h-40 bg-gray-100 overflow-hidden">
                                    <img src="{{ asset('storage/' . $resource->cover_image) }}" alt="{{ $resource->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="p-4">
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 leading-snug">{{ $resource->title }}</h3>
                                    <span class="shrink-0 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $resource->type_label }}
                                    </span>
                                </div>

                                @if(Auth::user()->isAdmin() && request('status'))
                                    <div class="mb-2">
                                        @if($resource->isPending())
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Pending</span>
                                        @elseif($resource->isRejected())
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($resource->categories as $cat)
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700">{{ $cat->name }}</span>
                                    @endforeach
                                </div>

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ Str::limit($resource->description, 120) ?: 'No description provided.' }}
                                </p>

                                <div class="text-xs text-gray-500 mb-4 space-y-1">
                                    @if($resource->author)
                                        <div>Author: {{ $resource->author }}</div>
                                    @endif
                                    <div>Posted by: {{ $resource->submitter?->name ?? 'Unknown' }}</div>
                                    @if($resource->isApproved() && $resource->reviewer)
                                        <div>Approved by: {{ $resource->reviewer->name }}</div>
                                    @endif
                                    <div>Added {{ $resource->created_at->format('M d, Y') }}</div>
                                </div>

                                <div class="flex justify-end pt-1 border-t border-gray-100">
                                    <a href="{{ route('spiritual-resources.show', $resource) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-primary/5 text-sm font-medium text-primary hover:bg-primary hover:text-white transition group">
                                        View Details
                                        <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-500">
                            <p class="text-lg mb-2">No resources found.</p>
                            <p class="text-sm">Try adjusting your filters or <a href="{{ route('spiritual-resources.suggest') }}" class="text-primary underline">suggest a new resource</a>.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $resources->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
