@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $contribution->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">Category: {{ $contribution->category->name ?? 'Uncategorized' }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('social-contributions.edit', $contribution->id) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                @endif
                <a href="{{ route('social-contributions.index') }}" class="text-indigo-600 hover:text-indigo-800">Back</a>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
            <div><span class="font-medium">Date:</span> {{ optional($contribution->date)->format('M d, Y') }}</div>
            <div><span class="font-medium">Target Amount:</span> {{ number_format($contribution->target_amount, 2) }}</div>
            <div><span class="font-medium">Location:</span> {{ $contribution->location ?: 'N/A' }}</div>
        </div>
        @php
            $collected = (float) ($contribution->collected_amount ?? 0);
            $target = (float) ($contribution->target_amount ?? 0);
            $percent = $target > 0 ? min(100, round(($collected / $target) * 100)) : 0;
            $left = max(0, $target - $collected);
            $exceeded = max(0, $collected - $target);
                        $barColor = $collected >= $target ? 'bg-green-600' : 'bg-primary';
        @endphp
        <div class="mt-4">
            <div class="text-sm text-gray-700">
                Collected: {{ number_format($collected, 2) }} ({{ $percent }}%)
                @if($exceeded > 0)
                    • Exceeded: <span class="text-green-700 font-medium">{{ number_format($exceeded, 2) }}</span>
                @else
                    • Left: {{ number_format($left, 2) }}
                @endif
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded h-2 overflow-hidden">
                <div class="{{ $barColor }} h-2" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        <div class="mt-4 prose max-w-none">
            {!! nl2br(e($contribution->description)) !!}
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">@if(Auth::user()->isAdmin()) Contributors @else Your Contribution @endif</h2>
            <div class="bg-white rounded-lg shadow">
                <div class="divide-y">
                    @forelse($contribution->contributors as $item)
                        <div class="p-4 flex items-start justify-between">
                            <div>
                                @if(Auth::user()->isAdmin())
                                    <div class="font-medium text-gray-900">{{ $item->user->name }}</div>
                                @endif
                                <div class="text-sm text-gray-600">Amount: {{ is_null($item->amount) ? '—' : number_format($item->amount, 2) }}</div>
                                @if($item->note)
                                    <div class="text-xs text-gray-500 mt-1">Note: {{ $item->note }}</div>
                                @endif
                                @if($item->image)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($item->image) }}" target="_blank" class="text-indigo-600 text-sm">View receipt</a>
                                    </div>
                                @endif
                            </div>
                            @if(Auth::user()->isAdmin())
                                <form action="{{ route('social-contributors.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this contribution?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-600">No contributors yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Contribute</h2>
            <div class="bg-white rounded-lg shadow p-4">
                <form method="POST" action="{{ route('social-contributions.contribute', $contribution->id) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @if(Auth::user()->isAdmin())
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User (optional)</label>
                            <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Select user</option>
                                @foreach(\App\Models\User::orderBy('name')->get(['id','name']) as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id')==$user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        @php
                            $defaultSingle = $contribution->single_amount ?? 0;
                            $min = $contribution->type === 'fixed' ? max($defaultSingle, 0) : 0;
                            $value = old('amount', $defaultSingle ?: $contribution->target_amount);
                        @endphp
                        <input type="number" step="0.01" min="{{ $min }}" name="amount" value="{{ $value }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required />
                        @if($contribution->type === 'fixed')
                            <p class="text-xs text-gray-500 mt-1">Minimum allowed (per user): {{ number_format($min, 2) }}</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">Suggested: {{ number_format($value, 2) }} (you may give less or more)</p>
                        @endif
                        @error('amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Note (optional)</label>
                        <textarea name="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('note') }}</textarea>
                        @error('note')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Receipt Image (optional)</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full" />
                        @error('image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">Submit</button>
                    </div>
                </form>
            </div>
            @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                        });
                    });
                </script>
            @endif
        </div>
    </div>
</div>
@endsection


