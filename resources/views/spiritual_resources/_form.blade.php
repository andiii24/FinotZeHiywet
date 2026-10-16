@php
    $resource = $resource ?? null;
    $selectedCategories = old('categories', $resource?->categories->pluck('id')->all() ?? []);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" id="title" value="{{ old('title', $resource?->title) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
    </div>

    <div>
        <label for="type" class="block text-sm font-medium text-gray-700">Resource Type <span class="text-red-500">*</span></label>
        <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
            <option value="">Select a type</option>
            @foreach(\App\Models\SpiritualResource::TYPES as $value => $label)
                <option value="{{ $value }}" {{ old('type', $resource?->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Categories <span class="text-red-500">*</span></label>
        <p class="text-xs text-gray-500 mb-2">Select one or more subject categories.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 border border-gray-200 rounded-md p-3 max-h-48 overflow-y-auto">
            @foreach($categories as $category)
                <label class="inline-flex items-start gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5"
                           {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                    <span>{{ $category->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <label for="author" class="block text-sm font-medium text-gray-700">Author / Contributor</label>
        <input type="text" name="author" id="author" value="{{ old('author', $resource?->author) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
               placeholder="Optional">
    </div>

    <div>
        <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
        <input type="text" name="source" id="source" value="{{ old('source', $resource?->source) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
               placeholder="Publisher, channel, website, etc.">
    </div>

    <div class="md:col-span-2">
        <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
        <input type="url" name="url" id="url" value="{{ old('url', $resource?->url) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
               placeholder="https://... (YouTube link, article link, etc.)">
        <p class="mt-1 text-xs text-gray-500">Required for YouTube videos, articles, and external links.</p>
    </div>

    <div>
        <label for="file" class="block text-sm font-medium text-gray-700">PDF Attachment</label>
        <input type="file" name="file" id="file" accept=".pdf,application/pdf"
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
        @if($resource?->file_path)
            <p class="mt-1 text-xs text-gray-500">Current file: <a href="{{ $resource->fileUrl() }}" target="_blank" class="text-blue-600 underline">View</a>. Upload a new file to replace it.</p>
        @else
            <p class="mt-1 text-xs text-gray-500">Optional. Use for PDF resources (max 20MB).</p>
        @endif
    </div>

    <div>
        <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
        <input type="file" name="cover_image" id="cover_image" accept="image/*"
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
        @if($resource?->cover_image)
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ $resource->coverUrl() }}" alt="Cover" class="h-16 w-12 object-cover rounded">
                <p class="text-xs text-gray-500">Upload a new image to replace the current cover.</p>
            </div>
        @else
            <p class="mt-1 text-xs text-gray-500">Optional. Recommended for books (max 5MB).</p>
        @endif
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Short Description</label>
        <textarea name="description" id="description" rows="4"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                  placeholder="Briefly describe what this resource offers...">{{ old('description', $resource?->description) }}</textarea>
    </div>
</div>

<div class="mt-8 flex flex-wrap items-center gap-3">
    <button type="submit"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-sm font-medium text-white shadow-sm hover:opacity-90 hover:shadow-md transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ $submitLabel }}
    </button>
</div>
