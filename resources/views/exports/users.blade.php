<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-file-export text-indigo-600"></i>
            {{ __('Filter & Export Residents') }}
        </h2>
    </x-slot>

    {{-- native <select> option background --}}
    <style>
        select.gs option { background-color: #1e3a8a; color: #fff; }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ── Page heading ────────────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-users-gear text-blue-300"></i>
                        Resident Export
                    </h1>
                    <p class="text-white/60 text-sm mt-1">Apply filters then download matching residents as Excel.</p>
                </div>

                @if (!$users->isEmpty())
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="backdrop-blur-md bg-white/20 border border-white/30 rounded-full px-4 py-1.5 text-sm font-semibold">
                            <i class="fa-solid fa-circle-check text-green-400 mr-1"></i>
                            {{ $users->count() }} {{ Str::plural('record', $users->count()) }} found
                        </span>
                        <a href="{{ route('exports.users.download', request()->all()) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/80 hover:bg-emerald-500 border border-emerald-400/50 font-semibold rounded-lg transition text-sm">
                            <i class="fa-solid fa-file-excel"></i> Download Excel
                        </a>
                    </div>
                @endif
            </div>

            {{-- ── Filter card (collapsible) ────────────────────────────── --}}
            @php
                $activeFilters = collect(request()->only([
                    'gender','age_range','marital_status','indigene',
                    'employment_status','education_level','income_bracket',
                    'has_disability','religion','ethnicity',
                ]))->filter()->count();
            @endphp

            <div x-data="{ open: true }"
                 class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl">

                {{-- toggle header --}}
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none">
                    <span class="font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-filter text-blue-300"></i>
                        Filter Options
                        @if ($activeFilters > 0)
                            <span class="bg-blue-500 text-white text-xs rounded-full px-2 py-0.5">
                                {{ $activeFilters }} active
                            </span>
                        @endif
                    </span>
                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"
                       class="fa-solid text-white/50 text-sm transition-transform"></i>
                </button>

                {{-- filter form --}}
                <div x-show="open" x-transition class="px-6 pb-6">
                    <div class="border-t border-white/20 pt-5">
                        <form method="GET" action="{{ route('exports') }}">
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-4">

                                {{-- Gender --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Gender</label>
                                    <select name="gender" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="male"   @selected(request('gender') == 'male')>Male</option>
                                        <option value="female" @selected(request('gender') == 'female')>Female</option>
                                    </select>
                                </div>

                                {{-- Age Range --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Age Range</label>
                                    <select name="age_range" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="18-25" @selected(request('age_range') == '18-25')>18–25</option>
                                        <option value="26-35" @selected(request('age_range') == '26-35')>26–35</option>
                                        <option value="36-50" @selected(request('age_range') == '36-50')>36–50</option>
                                        <option value="51+"   @selected(request('age_range') == '51+')>51+</option>
                                    </select>
                                </div>

                                {{-- Marital Status --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Marital Status</label>
                                    <select name="marital_status" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="single"   @selected(request('marital_status') == 'single')>Single</option>
                                        <option value="married"  @selected(request('marital_status') == 'married')>Married</option>
                                        <option value="divorced" @selected(request('marital_status') == 'divorced')>Divorced</option>
                                        <option value="widowed"  @selected(request('marital_status') == 'widowed')>Widowed</option>
                                    </select>
                                </div>

                                {{-- Indigene --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Indigene</label>
                                    <select name="indigene" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="1" @selected(request('indigene') == '1')>Indigene</option>
                                        <option value="0" @selected(request('indigene') == '0')>Non-Indigene</option>
                                    </select>
                                </div>

                                {{-- Employment --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Employment</label>
                                    <select name="employment_status" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="employed"      @selected(request('employment_status') == 'employed')>Employed</option>
                                        <option value="unemployed"    @selected(request('employment_status') == 'unemployed')>Unemployed</option>
                                        <option value="self_employed" @selected(request('employment_status') == 'self_employed')>Self-Employed</option>
                                        <option value="student"       @selected(request('employment_status') == 'student')>Student</option>
                                        <option value="retired"       @selected(request('employment_status') == 'retired')>Retired</option>
                                    </select>
                                </div>

                                {{-- Education --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Education</label>
                                    <select name="education_level" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="none"         @selected(request('education_level') == 'none')>None</option>
                                        <option value="primary"      @selected(request('education_level') == 'primary')>Primary</option>
                                        <option value="secondary"    @selected(request('education_level') == 'secondary')>Secondary</option>
                                        <option value="tertiary"     @selected(request('education_level') == 'tertiary')>Tertiary</option>
                                        <option value="postgraduate" @selected(request('education_level') == 'postgraduate')>Postgraduate</option>
                                    </select>
                                </div>

                                {{-- Income --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Income Bracket</label>
                                    <select name="income_bracket" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="low"    @selected(request('income_bracket') == 'low')>Low</option>
                                        <option value="middle" @selected(request('income_bracket') == 'middle')>Middle</option>
                                        <option value="high"   @selected(request('income_bracket') == 'high')>High</option>
                                    </select>
                                </div>

                                {{-- Disability --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Disability</label>
                                    <select name="has_disability" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="1" @selected(request('has_disability') == '1')>Has Disability</option>
                                        <option value="0" @selected(request('has_disability') == '0')>No Disability</option>
                                    </select>
                                </div>

                                {{-- Religion --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Religion</label>
                                    <select name="religion" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        <option value="christianity" @selected(request('religion') == 'christianity')>Christianity</option>
                                        <option value="islam"        @selected(request('religion') == 'islam')>Islam</option>
                                        <option value="traditional"  @selected(request('religion') == 'traditional')>Traditional</option>
                                        <option value="other"        @selected(request('religion') == 'other')>Other</option>
                                    </select>
                                </div>

                                {{-- Ethnicity --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-white/60 uppercase tracking-wide">Ethnicity</label>
                                    <select name="ethnicity" class="gs w-full bg-white/10 border border-white/30 rounded-lg text-white text-sm px-3 py-2 focus:outline-none focus:bg-white/20 focus:border-white/60 transition">
                                        <option value="">All</option>
                                        @foreach ($ethnicities as $eth)
                                            <option value="{{ $eth }}" @selected(request('ethnicity') == $eth)>
                                                {{ ucfirst($eth) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>{{-- /grid --}}

                            {{-- Action buttons --}}
                            <div class="flex flex-wrap items-center gap-3 mt-6 pt-4 border-t border-white/20">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg transition shadow text-sm">
                                    <i class="fa-solid fa-filter"></i> Apply Filters
                                </button>
                                <a href="{{ route('exports') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2 bg-white/20 hover:bg-white/30 border border-white/30 font-semibold rounded-lg transition text-sm">
                                    <i class="fa-solid fa-xmark"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>{{-- /filter card --}}

            {{-- ── Results ──────────────────────────────────────────────── --}}
            @if ($users->isEmpty())
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-12 text-center">
                    <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-white/20 mb-4">
                        <i class="fa-solid fa-users-slash text-3xl text-white/50"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white">No records found</h3>
                    <p class="text-white/60 text-sm mt-1">Try adjusting your filters to find matching residents.</p>
                    <a href="{{ route('exports') }}"
                       class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg text-sm transition">
                        <i class="fa-solid fa-rotate-left"></i> Clear all filters
                    </a>
                </div>

            @else
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl overflow-hidden">

                    {{-- table header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-white/20 gap-3">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="fa-solid fa-table-list text-blue-300"></i>
                            Results
                            <span class="text-sm font-normal text-white/60">
                                ({{ $users->count() }} {{ Str::plural('record', $users->count()) }})
                            </span>
                        </h3>
                        <a href="{{ route('exports.users.download', request()->all()) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/80 hover:bg-emerald-500 border border-emerald-400/50 font-semibold rounded-lg transition text-sm flex-shrink-0">
                            <i class="fa-solid fa-file-excel"></i> Download Excel
                        </a>
                    </div>

                    {{-- scrollable table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto text-sm">
                            <thead class="bg-white/10">
                                <tr>
                                    @foreach (['#','Name','Email','Age','Gender','Marital','Employment','Education','Income','Disability','Religion','Ethnicity','Indigene','Joined'] as $col)
                                        <th class="px-4 py-3 text-left text-white/70 font-semibold whitespace-nowrap text-xs uppercase tracking-wide">
                                            {{ $col }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach ($users as $i => $u)
                                    <tr class="hover:bg-white/10 transition-colors">

                                        {{-- # --}}
                                        <td class="px-4 py-3 text-white/40 text-xs">{{ $i + 1 }}</td>

                                        {{-- Name --}}
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ $u->firstname }} {{ $u->lastname }}
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-4 py-3 text-white/70 whitespace-nowrap">{{ $u->email }}</td>

                                        {{-- Age --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if (!empty($u->date_of_birth))
                                                {{ \Carbon\Carbon::parse($u->date_of_birth)->age }}
                                            @else
                                                <span class="text-white/30">—</span>
                                            @endif
                                        </td>

                                        {{-- Gender --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($u->gender === 'male')
                                                <span class="inline-flex items-center gap-1 text-blue-300">
                                                    <i class="fa-solid fa-mars text-xs"></i> Male
                                                </span>
                                            @elseif ($u->gender === 'female')
                                                <span class="inline-flex items-center gap-1 text-pink-300">
                                                    <i class="fa-solid fa-venus text-xs"></i> Female
                                                </span>
                                            @else
                                                <span class="text-white/30">—</span>
                                            @endif
                                        </td>

                                        {{-- Marital Status --}}
                                        <td class="px-4 py-3 text-white/80 capitalize whitespace-nowrap">
                                            {{ $u->marital_status ?? '—' }}
                                        </td>

                                        {{-- Employment --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $empClass = match($u->employment_status) {
                                                    'employed'      => 'bg-green-500/20 text-green-300 border-green-500/30',
                                                    'unemployed'    => 'bg-red-500/20 text-red-300 border-red-500/30',
                                                    'student'       => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                                    'self_employed' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                                                    'retired'       => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                                    default         => 'bg-white/10 text-white/40 border-white/20',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium border capitalize {{ $empClass }}">
                                                {{ str_replace('_', ' ', $u->employment_status ?? '—') }}
                                            </span>
                                        </td>

                                        {{-- Education --}}
                                        <td class="px-4 py-3 text-white/80 capitalize whitespace-nowrap">
                                            {{ $u->education_level ?? '—' }}
                                        </td>

                                        {{-- Income --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $incClass = match($u->income_bracket) {
                                                    'high'   => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                                    'middle' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                                                    'low'    => 'bg-red-500/20 text-red-300 border-red-500/30',
                                                    default  => 'bg-white/10 text-white/40 border-white/20',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium border capitalize {{ $incClass }}">
                                                {{ $u->income_bracket ?? '—' }}
                                            </span>
                                        </td>

                                        {{-- Disability --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($u->has_disability)
                                                <span class="inline-flex items-center gap-1 text-amber-300 text-xs">
                                                    <i class="fa-solid fa-wheelchair"></i> Yes
                                                </span>
                                            @else
                                                <span class="text-white/30 text-xs">No</span>
                                            @endif
                                        </td>

                                        {{-- Religion --}}
                                        <td class="px-4 py-3 text-white/80 capitalize whitespace-nowrap">
                                            {{ $u->religion ?? '—' }}
                                        </td>

                                        {{-- Ethnicity --}}
                                        <td class="px-4 py-3 text-white/80 capitalize whitespace-nowrap">
                                            {{ $u->ethnicity ?? '—' }}
                                        </td>

                                        {{-- Indigene --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($u->indigene)
                                                <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                                    Indigene
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-xs bg-white/10 text-white/50 border border-white/20">
                                                    Non-Indigene
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Joined --}}
                                        <td class="px-4 py-3 text-white/50 whitespace-nowrap text-xs">
                                            {{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>{{-- /overflow-x-auto --}}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
