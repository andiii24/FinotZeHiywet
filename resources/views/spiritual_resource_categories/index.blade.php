@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Resource Categories</h2>
                        <p class="text-sm text-gray-500 mt-1">Organize the Spiritual Resource Library by subject.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('spiritual-resource-categories.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-secondary text-sm font-semibold text-primary shadow-sm hover:brightness-95 hover:shadow-md transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            New Category
                        </a>
                        <a href="{{ route('spiritual-resources.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Library
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resources</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($category->description, 80) ?: '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->resources_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('spiritual-resource-categories.edit', $category) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-xs font-medium text-white shadow-sm hover:opacity-90 transition">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <button type="button"
                                                    onclick="deleteCategory({{ $category->id }}, @js($category->name), {{ $category->resources_count }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 transition">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No categories yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCategory(categoryId, name, resourceCount) {
    AppSwal.confirm({
        title: 'Delete Category?',
        html: `
            <div class="text-left">
                <p><strong>Category:</strong> ${name}</p>
                <p><strong>Linked resources:</strong> ${resourceCount}</p>
                <p class="text-amber-600 font-semibold mt-2">Resources will be kept and only unlinked from this category.</p>
            </div>
        `,
        icon: 'warning',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete Category',
    }).then((result) => {
        if (result.isConfirmed) {
            AppSwal.post(`/spiritual-resource-categories/${categoryId}`, { _method: 'DELETE' });
        }
    });
}
</script>
@endsection
