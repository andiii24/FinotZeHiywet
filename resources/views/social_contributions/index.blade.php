@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Social Contributions</h1>
        @if(Auth::user() && Auth::user()->isAdmin())
            <a href="{{ route('social-contributions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                New Contribution
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($contributions as $contribution)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $contribution->title }}</h2>
                    <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">{{ $contribution->category->name ?? 'Uncategorized' }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-2 line-clamp-3">{{ $contribution->description }}</p>
                <div class="mt-3 text-sm text-gray-500">
                    <div>Date: {{ optional($contribution->date)->format('M d, Y') }}</div>
                    <div>Target Amount: {{ number_format($contribution->target_amount, 2) }}</div>
                    @php
                        $collected = (float) ($contribution->collected_amount ?? 0);
                        $target = (float) ($contribution->target_amount ?? 0);
                        $percent = $target > 0 ? min(100, round(($collected / $target) * 100)) : 0;
                        $left = max(0, $target - $collected);
                        $exceeded = max(0, $collected - $target);
                        $barColor = $collected >= $target ? 'bg-green-600' : 'bg-indigo-600';
                    @endphp
                    <div class="mt-1">
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
                    @if(!Auth::user()->isAdmin())
                        @php $my = optional($contribution->contributors->first()); @endphp
                        <div class="mt-1">Your Contribution: {{ is_null(optional($my)->amount) ? '—' : number_format($my->amount, 2) }}</div>
                    @endif
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('social-contributions.show', $contribution) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View</a>
                    <span class="text-xs text-gray-400">Location: {{ $contribution->location ?: 'N/A' }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="bg-white p-6 rounded shadow text-center text-gray-600">No social contributions yet.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $contributions->links() }}</div>
</div>
@endsection


