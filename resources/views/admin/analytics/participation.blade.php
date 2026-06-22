<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Community Participation Trends</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-4">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <label class="flex flex-col text-sm">
                        Window (months)
                        <select name="months" class="rounded-md text-gray-900 px-2 py-2 text-sm mt-1">
                            @foreach ([3, 6, 12, 18, 24, 36] as $m)
                                <option value="{{ $m }}" @selected((int) $months === $m)>{{ $m }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="px-3 py-2 bg-indigo-600 rounded-md text-sm hover:bg-indigo-700">Apply</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-stat-card title="Civic Participation (sum)" :value="$totals['civic']" />
                <x-stat-card title="Volunteer Activities (sum)" :value="$totals['volunteer']" />
                <x-stat-card title="Complaints raised" :value="$totals['complaints']" />
                <x-stat-card title="New project memberships" :value="$totals['projects']" />
            </div>

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-white mb-2">Participation indicators over time</h3>
                <canvas id="participationChart" height="120"></canvas>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-2">Complaints by category (window)</h3>
                    <canvas id="categoryChart" height="160"></canvas>
                </div>
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-2">Project memberships per month</h3>
                    <canvas id="projectsChart" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels          = @json($labels);
        const civicSeries     = @json($civicSeries);
        const volunteerSeries = @json($volunteerSeries);
        const complaintSeries = @json($complaintSeries);
        const projectMembers  = @json($projectMembers);
        const byCategory      = @json($byCategory);

        new Chart(document.getElementById('participationChart'), {
            type: 'line',
            data: { labels, datasets: [
                { label: 'Civic Participation',  data: civicSeries,     borderColor: '#60a5fa', backgroundColor: 'rgba(96,165,250,0.2)', tension: 0.3 },
                { label: 'Volunteer Activities', data: volunteerSeries, borderColor: '#34d399', backgroundColor: 'rgba(52,211,153,0.2)', tension: 0.3 },
                { label: 'Complaints',           data: complaintSeries, borderColor: '#fbbf24', backgroundColor: 'rgba(251,191,36,0.2)', tension: 0.3 },
            ] },
            options: { responsive: true, plugins: { legend: { labels: { color: '#fff' } } },
                scales: { x: { ticks: { color: '#fff' } }, y: { ticks: { color: '#fff' }, beginAtZero: true } } }
        });

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: { labels: Object.keys(byCategory), datasets: [{ data: Object.values(byCategory) }] },
            options: { plugins: { legend: { labels: { color: '#fff' } } } }
        });

        new Chart(document.getElementById('projectsChart'), {
            type: 'bar',
            data: { labels, datasets: [{ label: 'New memberships', data: projectMembers, backgroundColor: '#a78bfa' }] },
            options: { plugins: { legend: { labels: { color: '#fff' } } },
                scales: { x: { ticks: { color: '#fff' } }, y: { ticks: { color: '#fff' }, beginAtZero: true } } }
        });
    </script>
    @endpush
</x-app-layout>
