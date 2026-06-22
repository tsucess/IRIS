<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Decision Support</h2>
    </x-slot>

    @php
        $sevClass = [
            'critical' => 'bg-red-600/80 border-red-300',
            'high'     => 'bg-orange-500/80 border-orange-300',
            'medium'   => 'bg-yellow-500/80 border-yellow-300',
            'low'      => 'bg-blue-500/80 border-blue-300',
        ];
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Insight cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Residents Profiled" :value="$insights['residents_total']" />
                <x-stat-card title="No Electricity %" :value="$insights['no_electricity_pct'] . '%'" />
                <x-stat-card title="No Clean Water %" :value="$insights['no_water_pct'] . '%'" />
                <x-stat-card title="No Sanitation %" :value="$insights['no_sanitation_pct'] . '%'" />
                <x-stat-card title="Civic Participation %" :value="$insights['civic_rate'] . '%'" />
                <x-stat-card title="Volunteer %" :value="$insights['volunteer_rate'] . '%'" />
                <x-stat-card title="Open Complaints" :value="$insights['open_complaints']" />
                <x-stat-card title="Urgent Unresolved" :value="$insights['urgent_complaints']" />
                <x-stat-card title="Overdue Projects" :value="$insights['overdue_projects']" />
                <x-stat-card title="Overdue Tasks" :value="$insights['overdue_tasks']" />
                <x-stat-card title="Over-Allocated Resources" :value="$insights['over_allocated']" />
            </div>

            {{-- Recommendations --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-bold mb-4">Recommended Actions</h3>

                @if (empty($recommendations))
                    <p class="text-white/80">No critical issues detected. The community indicators are within healthy thresholds.</p>
                @else
                    <ul class="space-y-3">
                        @foreach ($recommendations as $r)
                            <li class="flex items-start gap-3 p-4 rounded-lg border {{ $sevClass[$r['severity']] ?? 'bg-gray-500/80 border-gray-300' }}">
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-white/30">
                                    {{ $r['severity'] }}
                                </span>
                                <div class="flex-1">
                                    <h4 class="font-semibold">{{ $r['title'] }}</h4>
                                    <p class="text-sm text-white/90">{{ $r['message'] }}</p>
                                </div>
                                @if (! empty($r['url']))
                                    <a href="{{ $r['url'] }}"
                                        class="px-3 py-1 bg-white/30 hover:bg-white/50 rounded text-sm whitespace-nowrap">
                                        Take action →
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Top complaint categories --}}
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                    <h3 class="text-lg font-bold mb-4">Top Complaint Categories</h3>
                    @if ($topComplaints->isEmpty())
                        <p class="text-white/80">No complaint data yet.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($topComplaints as $category => $total)
                                <li class="flex justify-between items-center bg-white/10 px-3 py-2 rounded">
                                    <span class="capitalize">{{ $category ?? 'Uncategorised' }}</span>
                                    <span class="font-bold">{{ $total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- At-risk projects --}}
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                    <h3 class="text-lg font-bold mb-4">At-Risk Projects</h3>
                    @if ($riskProjects->isEmpty())
                        <p class="text-white/80">No at-risk projects.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach ($riskProjects as $p)
                                <li class="bg-white/10 px-3 py-2 rounded">
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('projects.show', $p) }}" class="font-semibold underline">{{ $p->title }}</a>
                                        <span class="capitalize text-xs px-2 py-1 bg-white/30 rounded">{{ str_replace('_',' ', $p->status) }}</span>
                                    </div>
                                    <div class="text-xs text-white/80 mt-1">
                                        Ends {{ optional($p->end_date)->format('Y-m-d') ?? '—' }}
                                        @if ($p->budget && $p->actual_cost && $p->actual_cost > $p->budget)
                                            · <span class="text-red-200">Over budget</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
