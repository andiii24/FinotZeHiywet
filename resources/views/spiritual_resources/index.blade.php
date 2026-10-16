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
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('spiritual-resources.my-submissions') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition text-sm">My Submissions</a>
                        <a href="{{ route('spiritual-resources.suggest') }}" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition text-sm">Suggest a Resource</a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('spiritual-resources.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm">Add Resource</a>
                            @if($pendingCount > 0)
                                <a href="{{ route('spiritual-resources.index', ['status' => 'pending']) }}" class="px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600 transition text-sm">
                                    Pending Reviews ({{ $pendingCount }})
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

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
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition">Filter</button>
                            <a href="{{ route('spiritual-resources.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Clear</a>
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
                                    <div>Added {{ $resource->created_at->format('M d, Y') }}</div>
                                </div>

                                <div class="flex justify-end">
                                    <a href="{{ route('spiritual-resources.show', $resource) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm">View Details</a>
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
