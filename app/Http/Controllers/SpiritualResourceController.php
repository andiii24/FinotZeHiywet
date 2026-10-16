<?php

namespace App\Http\Controllers;

use App\Models\SpiritualResource;
use App\Models\SpiritualResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SpiritualResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except([
            'index',
            'show',
            'suggest',
            'storeSuggestion',
            'mySubmissions',
        ]);
    }

    public function index(Request $request)
    {
        $query = SpiritualResource::with(['categories', 'submitter', 'reviewer']);

        if (Auth::user()->isAdmin() && $request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->approved();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('spiritual_resource_categories.id', $request->category);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $resources = $query->latest()->paginate(9)->withQueryString();
        $categories = SpiritualResourceCategory::orderBy('name')->get();
        $pendingCount = Auth::user()->isAdmin()
            ? SpiritualResource::pending()->count()
            : 0;

        return view('spiritual_resources.index', compact(
            'resources',
            'categories',
            'pendingCount'
        ));
    }

    public function create()
    {
        $categories = SpiritualResourceCategory::orderBy('name')->get();

        return view('spiritual_resources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateResource($request);
        $this->assertNoDuplicate($validated['url'] ?? null, $validated['title'], $validated['type']);

        $data = $this->prepareResourceData($request, $validated);
        $data['status'] = SpiritualResource::STATUS_APPROVED;
        $data['submitted_by'] = Auth::id();
        $data['reviewed_by'] = Auth::id();
        $data['reviewed_at'] = now();

        $resource = SpiritualResource::create($data);
        $resource->categories()->sync($validated['categories']);

        return redirect()->route('spiritual-resources.show', $resource)
            ->with('success', 'Resource added to the library.');
    }

    public function show(string $id)
    {
        $resource = SpiritualResource::with(['categories', 'submitter', 'reviewer'])
            ->findOrFail($id);

        $user = Auth::user();
        $canView = $resource->isApproved()
            || $user->isAdmin()
            || $resource->submitted_by === $user->id;

        if (!$canView) {
            abort(403, 'You do not have permission to view this resource.');
        }

        return view('spiritual_resources.show', compact('resource'));
    }

    public function edit(string $id)
    {
        $resource = SpiritualResource::with('categories')->findOrFail($id);
        $categories = SpiritualResourceCategory::orderBy('name')->get();

        return view('spiritual_resources.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $resource = SpiritualResource::findOrFail($id);
        $validated = $this->validateResource($request);
        $this->assertNoDuplicate(
            $validated['url'] ?? null,
            $validated['title'],
            $validated['type'],
            $resource->id
        );

        $data = $this->prepareResourceData($request, $validated, $resource);
        $resource->update($data);
        $resource->categories()->sync($validated['categories']);

        return redirect()->route('spiritual-resources.show', $resource)
            ->with('success', 'Resource updated successfully.');
    }

    public function destroy(string $id)
    {
        $resource = SpiritualResource::findOrFail($id);

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        if ($resource->cover_image) {
            Storage::disk('public')->delete($resource->cover_image);
        }

        $resource->categories()->detach();
        $resource->delete();

        return redirect()->route('spiritual-resources.index')
            ->with('success', 'Resource deleted successfully.');
    }

    public function suggest()
    {
        $categories = SpiritualResourceCategory::orderBy('name')->get();

        return view('spiritual_resources.suggest', compact('categories'));
    }

    public function storeSuggestion(Request $request)
    {
        $validated = $this->validateResource($request);
        $this->assertNoDuplicate($validated['url'] ?? null, $validated['title'], $validated['type']);

        $data = $this->prepareResourceData($request, $validated);
        $data['status'] = SpiritualResource::STATUS_PENDING;
        $data['submitted_by'] = Auth::id();

        $resource = SpiritualResource::create($data);
        $resource->categories()->sync($validated['categories']);

        return redirect()->route('spiritual-resources.my-submissions')
            ->with('success', 'Your resource suggestion has been submitted for review.');
    }

    public function mySubmissions()
    {
        $resources = SpiritualResource::with(['categories', 'reviewer'])
            ->where('submitted_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('spiritual_resources.my_submissions', compact('resources'));
    }

    public function review(Request $request, string $id)
    {
        $resource = SpiritualResource::findOrFail($id);

        if (!$resource->isPending()) {
            return redirect()->route('spiritual-resources.show', $resource)
                ->with('error', 'This resource has already been reviewed.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'nullable|required_if:action,reject|string|max:1000',
        ]);

        if ($validated['action'] === 'approve') {
            $resource->update([
                'status' => SpiritualResource::STATUS_APPROVED,
                'rejection_reason' => null,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            return redirect()->route('spiritual-resources.show', $resource)
                ->with('success', 'Resource approved and added to the library.');
        }

        $resource->update([
            'status' => SpiritualResource::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('spiritual-resources.index', ['status' => 'pending'])
            ->with('success', 'Resource suggestion rejected.');
    }

    private function validateResource(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(array_keys(SpiritualResource::TYPES))],
            'url' => 'nullable|url|max:1000',
            'author' => 'nullable|string|max:500',
            'source' => 'nullable|string|max:500',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:spiritual_resource_categories,id',
            'file' => 'nullable|file|mimes:pdf|max:20480',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
        ]);
    }

    private function assertNoDuplicate(?string $url, string $title, string $type, ?int $excludeId = null): void
    {
        if (SpiritualResource::hasDuplicate($url, $title, $type, $excludeId)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'title' => 'A similar resource already exists in the library or is pending review.',
            ]);
        }
    }

    private function prepareResourceData(Request $request, array $validated, ?SpiritualResource $existing = null): array
    {
        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'url' => $validated['url'] ?? null,
            'author' => $validated['author'] ?? null,
            'source' => $validated['source'] ?? null,
        ];

        if ($request->hasFile('file')) {
            if ($existing && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $data['file_path'] = $request->file('file')->store('spiritual_resources', 'public');
        } elseif ($existing) {
            $data['file_path'] = $existing->file_path;
        }

        if ($request->hasFile('cover_image')) {
            if ($existing && $existing->cover_image) {
                Storage::disk('public')->delete($existing->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('spiritual_resource_covers', 'public');
        } elseif ($existing) {
            $data['cover_image'] = $existing->cover_image;
        }

        return $data;
    }
}
