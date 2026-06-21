<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📅 Project Calendar</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 p-6">
        <div class="max-w-7xl mx-auto">

            {{-- Legend --}}
            <div class="flex flex-wrap gap-4 mb-4 text-sm font-medium text-white">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span> Pending</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span> In Progress</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-400 inline-block"></span> Completed</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Cancelled</span>
                <a href="{{ route('projects.index') }}" class="ml-auto px-4 py-1.5 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg text-white">
                    ← Back to List
                </a>
            </div>

            {{-- Calendar Container --}}
            <div class="backdrop-blur-lg bg-white rounded-xl shadow-xl p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('vendor/fullcalendar/index.global.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const events = @json($events);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left:   'prev,next today',
                    center: 'title',
                    right:  'dayGridMonth,timeGridWeek,listMonth'
                },
                events: events,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                height: 'auto',
                eventDisplay: 'block',
            });

            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
