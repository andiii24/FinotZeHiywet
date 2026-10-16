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
        <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
        <div class="mt-2 rounded-md border border-secondary/40 bg-secondary/10 px-3 py-2 text-xs text-gray-700 space-y-1">
            <p><span class="font-semibold text-primary">Recommended size:</span> 1200 × 675 pixels (16:9)</p>
            <p>Images are cropped to a standard 16:9 frame in the library. Max file size: 5MB (JPG, PNG, or WebP).</p>
        </div>
        <div class="mt-3">
            <p class="text-xs font-medium text-gray-600 mb-1.5">Preview</p>
            <div class="relative w-full max-w-sm h-40 rounded-lg overflow-hidden bg-gradient-to-br from-primary/10 to-secondary/20 border border-gray-200">
                <img id="cover-preview"
                     src="{{ $resource?->cover_image ? $resource->coverUrl() : '' }}"
                     alt="Cover preview"
                     class="h-full w-full object-cover object-center {{ $resource?->cover_image ? '' : 'hidden' }}">
                <div id="cover-preview-placeholder"
                     class="absolute inset-0 flex flex-col items-center justify-center text-primary/35 {{ $resource?->cover_image ? 'hidden' : '' }}">
                    <svg class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs">1200 × 675 preview</span>
                </div>
            </div>
            @if($resource?->cover_image)
                <p class="mt-1.5 text-xs text-gray-500">Current cover shown. Choose a new file to replace it.</p>
            @endif
        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cover_image');
    const preview = document.getElementById('cover-preview');
    const placeholder = document.getElementById('cover-preview-placeholder');
    if (!input || !preview || !placeholder) return;

    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) return;

        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
        preview.onload = function () {
            URL.revokeObjectURL(url);
        };
    });
});
</script>
