<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Project;
use App\Models\Street;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // ── Resident dashboard (non-admin users) ─────────────────────────────
        if (! $user->isAdmin()) {
            $announcements = Announcement::with('author')
                ->active()
                ->visibleTo($user)
                ->orderByDesc('pinned')
                ->orderByDesc('created_at')
                ->take(4)
                ->get();

            $myProjects = $user->projects()
                ->withCount('tasks')
                ->latest()
                ->take(5)
                ->get();

            return view('resident.dashboard', compact('announcements', 'myProjects'));
        }

        // ── Admin dashboard ───────────────────────────────────────────────────
        $zoneFilter = $request->get('zone');

        // Create cache key based on zone filter
        $cacheKey = 'dashboard_data_'.($zoneFilter ?? 'all');

        // Cache dashboard data for 5 minutes
        $dashboardData = Cache::remember($cacheKey, 300, function () use ($zoneFilter) {
            // Streets with users & projects
            $streetsQuery = Street::query();

            if ($zoneFilter) {
                $streetsQuery->where('zone', $zoneFilter);
            }

            $streetsData = $streetsQuery
                ->withCount(['users', 'projects'])
                ->get();

            $totalUsers = $streetsData->sum('users_count');
            $totalProjects = $streetsData->sum('projects_count');

            // Role counts (from users)
            $roleCountsChart = User::select('role', DB::raw('count(*) as total'))
                ->when($zoneFilter, function ($query) use ($zoneFilter) {
                    $query->whereHas('street', function ($q) use ($zoneFilter) {
                        $q->where('zone', $zoneFilter);
                    });
                })
                ->groupBy('role')
                ->pluck('total', 'role');

            $roleCounts = User::select('role')
                ->distinct()
                ->count('role');

            // Latest users
            $latestUsers = User::with('street')
                ->when($zoneFilter, function ($query) use ($zoneFilter) {
                    $query->whereHas('street', function ($q) use ($zoneFilter) {
                        $q->where('zone', $zoneFilter);
                    });
                })
                ->latest()
                ->take(10)
                ->get();

            // ✅ OPTIMIZED: Single query for all demographics
            $demographics = DB::table('resident_extended')
                ->select(
                    'gender',
                    'marital_status',
                    'ethnicity',
                    'religion',
                    'indigene',
                    'education_level',
                    'employment_status',
                    'occupation',
                    'income_bracket',
                    'access_to_electricity',
                    'access_to_clean_water',
                    'access_to_sanitation'
                )
                ->get();

            // Process demographics data
            $genderRatio = $demographics->groupBy('gender')->map->count();
            $maritalStatus = $demographics->groupBy('marital_status')->map->count();
            $ethnicityDist = $demographics->groupBy('ethnicity')->map->count();
            $religionDist = $demographics->groupBy('religion')->map->count();
            $indigeneDist = $demographics->groupBy('indigene')->map->count();
            $educationLevels = $demographics->groupBy('education_level')->map->count();
            $employmentStatus = $demographics->groupBy('employment_status')->map->count();
            $occupationDist = $demographics->groupBy('occupation')->map->count();
            $incomeBrackets = $demographics->groupBy('income_bracket')->map->count();

            // Infrastructure counts
            $infrastructure = (object) [
                'electricity' => $demographics->where('access_to_electricity', 1)->count(),
                'clean_water' => $demographics->where('access_to_clean_water', 1)->count(),
                'sanitation' => $demographics->where('access_to_sanitation', 1)->count(),
            ];

            // ✅ GROWTH & TRENDS
            // Use database-agnostic date formatting
            $driverName = DB::connection()->getDriverName();

            if ($driverName === 'sqlite') {
                $monthlyNewResidents = User::select(
                    DB::raw("strftime('%Y-%m', created_at) as month"),
                    DB::raw('COUNT(*) as total')
                )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month');
            } else {
                // MySQL, PostgreSQL, etc.
                $monthlyNewResidents = User::select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('COUNT(*) as total')
                )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month');
            }

            $populationPerZone = Street::select('zone', DB::raw('COUNT(users.id) as total'))
                ->join('users', 'streets.id', '=', 'users.street_id')
                ->groupBy('zone')
                ->pluck('total', 'zone');

            // Project analytics
            $projectStatusChart = Project::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $budgetData = Project::whereNotNull('budget')
                ->select('title', 'budget', 'actual_cost')
                ->orderByDesc('budget')
                ->limit(10)
                ->get();

            // Task analytics
            $taskStatusChart = Task::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $taskPriorityChart = Task::select('priority', DB::raw('count(*) as total'))
                ->whereNotNull('priority')
                ->groupBy('priority')
                ->pluck('total', 'priority');

            return compact(
                'streetsData',
                'totalUsers',
                'totalProjects',
                'roleCounts',
                'roleCountsChart',
                'latestUsers',
                'genderRatio',
                'maritalStatus',
                'ethnicityDist',
                'religionDist',
                'indigeneDist',
                'educationLevels',
                'employmentStatus',
                'occupationDist',
                'incomeBrackets',
                'infrastructure',
                'monthlyNewResidents',
                'populationPerZone',
                'projectStatusChart',
                'budgetData',
                'taskStatusChart',
                'taskPriorityChart'
            );
        });

        // Distinct zones for filter dropdown (not cached as it's small and rarely changes)
        $zones = Street::whereNotNull('zone')->distinct()->pluck('zone');

        return view('admin.dashboard', array_merge(
            ['zoneFilter' => $zoneFilter, 'zones' => $zones],
            $dashboardData
        ));
    }
}
