<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Apple Glassmorphism Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Card -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-xl font-bold">
                    {{ __('Welcome to iris, ') }}
                    <span class="text-dark">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span> 🎉
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 fade-in">
                <x-stat-card title="Total Residents" :value="$totalUsers" />
                <x-stat-card title="Total Streets" :value="$streetsData->count()" />
                <x-stat-card title="Roles Count" :value="$roleCounts" />
                <x-stat-card title="Projects" :value="$totalProjects" />
            </div>

            <!-- Filter Form -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6 mb-3 mt-2">
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2">
                    <div>
                        <label for="zone" class="font-semibold">Filter by Zone:</label>
                        <select name="zone" id="zone"
                            class="border-gray-300 rounded-md shadow-sm text-black px-2" style="width: 10rem;">
                            <option value="">All Zones </option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone }}" {{ $zone == $zoneFilter ? 'selected' : '' }}>
                                    {{ $zone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-200 underline hover:text-white">
                        Clear Filter
                    </a>
                </form>
                <a href="{{ route('exports') }}" class="text-sm text-blue-200 underline hover:text-white">
                        Filter Data
                    </a>
            </div>




            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Users Per Street -->
                <x-glass-card class="lg:col-span-3 backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Users Per Street</h3>
                    <canvas id="usersPerStreetChart" height="120"></canvas>
                </x-glass-card>

                <!-- Roles -->
                <x-glass-card class="bg-white border border-white/50 rounded-xl shadow-xl p-4 p-0">
                    {{-- <h3 class="text-lg font-bold mb-4 text-white">Users By Role</h3> --}}
                    <canvas id="roleChart" height="120"></canvas>
                </x-glass-card>

                <!-- Gender -->
                <x-glass-card class="backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Gender Distribution</h3>
                    <canvas id="genderChart" height="120"></canvas>
                </x-glass-card>

                <!-- Marital Status -->
                <x-glass-card class="backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Marital Status</h3>
                    <canvas id="maritalChart" height="120"></canvas>
                </x-glass-card>

                <!-- Indigene -->
                <x-glass-card class="bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Indigene vs Non-Indigene</h3>
                    <canvas id="indigeneChart" height="120"></canvas>
                </x-glass-card>

                <!-- Education -->
                <x-glass-card class="backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Education Levels</h3>
                    <canvas id="educationChart" height="120"></canvas>
                </x-glass-card>

                <!-- Employment -->
                <x-glass-card class="backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Employment Status</h3>
                    <canvas id="employmentChart" height="140"></canvas>
                </x-glass-card>

                <!-- Zone Population -->
                {{-- <x-glass-card class="md:col-span-2 backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0"> --}}
                <x-glass-card class="lg:col-span-3 backdrop-blur-lg bg-white border border-white/50 rounded-xl shadow-xl p-0">
                    <h3 class="text-lg font-bold text-white">Population Per Zone</h3>
                    <canvas id="zoneChart" height="140"></canvas>
                </x-glass-card>
            </div>

            <!-- Infrastructure -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-white">Infrastructure Access</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 fade-in">
                <x-stat-card title="Electricity" :value="$infrastructure->electricity" />
                <x-stat-card title="Clean Water" :value="$infrastructure->clean_water" />
                <x-stat-card title="Sanitation" :value="$infrastructure->sanitation" />
            </div>

            <!-- Latest Residents -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/20">
                    <h3 class="text-lg font-bold text-white">Latest Residents</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left text-white">Name</th>
                                <th class="px-4 py-3 text-left text-white">Email</th>
                                <th class="px-4 py-3 text-left text-white">Street</th>
                                <th class="px-4 py-3 text-left text-white">Role</th>
                                <th class="px-4 py-3 text-left text-white">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($latestUsers as $user)
                                <tr class="hover:bg-white/10 transition text-sm">
                                    <td class="px-4 py-2">{{ $user->firstname }} {{ $user->lastname }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->street->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                                    <td class="px-4 py-2">{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-white/60">No users yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (hidden) -->
    <div class="modal fade hidden" id="chartModal" tabindex="-1" aria-labelledby="chartModalLabel" aria-hidden="true" style="display:none!important">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartModalLabel">Detailed Chart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <canvas id="modalChartCanvas"></canvas>
                </div>
            </div>
        </div>
    </div>

    @php
        $indigeneLabels = $indigeneDist->mapWithKeys(function ($value, $key) {
            return [$key == 1 ? 'Indigenes' : 'Non-Indigenes' => $value];
        });
    @endphp


    @push('scripts')
        {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
        <script>
            function makeChart(id, type, labels, data, label) {
                new Chart(document.getElementById(id), {
                    type: type,
                    data: {
                        labels: labels.map(label =>
                            label.length > 10 ? label.substring(0, 10) + '…' : label
                        ),

                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: [
                                '#60a5fa', '#34d399', '#fbbf24', '#f87171', '#a78bfa', '#f472b6'
                            ]
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }



            // Existing
            makeChart('usersPerStreetChart', 'line',
                {!! json_encode($streetsData->pluck('name')) !!},
                {!! json_encode($streetsData->pluck('users_count')) !!},
                'Residents'
            );
            makeChart('roleChart', 'doughnut',
                {!! json_encode($roleCountsChart->keys()) !!},
                {!! json_encode($roleCountsChart->values()) !!},
                'Roles'
            );

            // New
            makeChart('genderChart', 'pie',
                {!! json_encode(array_keys($genderRatio->toArray())) !!},
                {!! json_encode(array_values($genderRatio->toArray())) !!},
                'Gender'
            );
            makeChart('maritalChart', 'doughnut',
                {!! json_encode(array_keys($maritalStatus->toArray())) !!},
                {!! json_encode(array_values($maritalStatus->toArray())) !!},
                'Marital Status'
            );
            makeChart('indigeneChart', 'pie',
                {!! json_encode(array_keys($indigeneLabels->toArray())) !!},
                {!! json_encode(array_values($indigeneDist->toArray())) !!},
                'Indigene'
            );
            makeChart('educationChart', 'doughnut',
                {!! json_encode(array_keys($educationLevels->toArray())) !!},
                {!! json_encode(array_values($educationLevels->toArray())) !!},
                'Education'
            );
            makeChart('employmentChart', 'doughnut',
                {!! json_encode(array_keys($employmentStatus->toArray())) !!},
                {!! json_encode(array_values($employmentStatus->toArray())) !!},
                'Employment'
            );
            makeChart('zoneChart', 'line',
                {!! json_encode(array_keys($populationPerZone->toArray())) !!},
                {!! json_encode(array_values($populationPerZone->toArray())) !!},
                'Population'
            );
        </script>
    @endpush
</x-app-layout>
