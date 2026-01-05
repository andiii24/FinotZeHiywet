@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ Auth::user()->isAdmin() ? 'All Contributors' : 'My Contributions' }}</h1>
        <form method="GET" action="{{ route('social-contributors.index') }}" class="flex items-center space-x-2">
            <select name="contribution_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Contributions</option>
                @isset($allContributions)
                    @foreach($allContributions as $c)
                        <option value="{{ $c->id }}" @selected(($contributionId ?? '')==$c->id)>{{ $c->title }}</option>
                    @endforeach
                @endisset
            </select>
            <button class="px-3 py-2 bg-indigo-600 text-white rounded-md">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if(Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contribution</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($contributors as $item)
                    <tr>
                        @if(Auth::user()->isAdmin())
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->user->name }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('social-contributions.show', $item->socialContribution->id) }}" class="text-indigo-600 hover:text-indigo-800">{{ $item->socialContribution->title }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ is_null($item->amount) ? '—' : number_format($item->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($item->created_at)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($item->image)
                                <a href="{{ Storage::url($item->image) }}" target="_blank" class="text-indigo-600">View</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <form action="{{ route('social-contributors.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this contribution?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-600">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $contributors->links() }}</div>
</div>
@endsection


