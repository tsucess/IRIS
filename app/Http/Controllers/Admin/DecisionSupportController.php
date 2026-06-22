<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Project;
use App\Models\ResidentExtended;
use App\Models\ResourceAllocation;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DecisionSupportController extends Controller
{
    public function index(Request $request)
    {
        $payload = Cache::remember('decision_support_v1', 300, function () {
            return [
                'insights'        => $this->buildInsights(),
                'recommendations' => $this->buildRecommendations(),
                'topComplaints'   => $this->topComplaintCategories(),
                'riskProjects'    => $this->riskProjects(),
            ];
        });

        return view('admin.decision-support.index', $payload);
    }

    /**
     * High-level numeric insights surfaced as cards.
     */
    private function buildInsights(): array
    {
        $totalResidents = ResidentExtended::count() ?: 1;

        $noElectricity = ResidentExtended::where('access_to_electricity', false)->count();
        $noWater       = ResidentExtended::where('access_to_clean_water', false)->count();
        $noSanitation  = ResidentExtended::where('access_to_sanitation', false)->count();

        $civicRate      = round(ResidentExtended::where('civic_participation', true)->count() / $totalResidents * 100, 1);
        $volunteerRate  = round(ResidentExtended::where('volunteer_activities', true)->count() / $totalResidents * 100, 1);

        $openComplaints   = Complaint::where('status', 'open')->count();
        $urgentComplaints = Complaint::where('priority', 'urgent')->whereIn('status', ['open', 'in_review'])->count();

        $overdueTasks   = Task::whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['done', 'completed', 'cancelled'])
            ->count();
        $overdueProjects = Project::whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        $overAllocated = ResourceAllocation::whereColumn('used_amount', '>', 'allocated_amount')->count();

        return [
            'residents_total'        => ResidentExtended::count(),
            'no_electricity_pct'     => round($noElectricity / $totalResidents * 100, 1),
            'no_water_pct'           => round($noWater / $totalResidents * 100, 1),
            'no_sanitation_pct'      => round($noSanitation / $totalResidents * 100, 1),
            'civic_rate'             => $civicRate,
            'volunteer_rate'         => $volunteerRate,
            'open_complaints'        => $openComplaints,
            'urgent_complaints'      => $urgentComplaints,
            'overdue_tasks'          => $overdueTasks,
            'overdue_projects'       => $overdueProjects,
            'over_allocated'         => $overAllocated,
        ];
    }

    /**
     * Rule-based recommendations ranked by severity.
     */
    private function buildRecommendations(): array
    {
        $i = $this->buildInsights();
        $recs = [];

        if ($i['no_water_pct'] >= 25) {
            $recs[] = $this->rec('critical', 'Clean Water Access',
                "{$i['no_water_pct']}% of residents lack clean water. Prioritise a water infrastructure project.",
                route('projects.create'));
        }
        if ($i['no_electricity_pct'] >= 25) {
            $recs[] = $this->rec('critical', 'Electricity Coverage',
                "{$i['no_electricity_pct']}% of residents lack electricity. Consider an electrification programme.",
                route('projects.create'));
        }
        if ($i['no_sanitation_pct'] >= 30) {
            $recs[] = $this->rec('high', 'Sanitation Gap',
                "{$i['no_sanitation_pct']}% of residents lack sanitation. Plan a sanitation drive or facility.",
                route('projects.create'));
        }
        if ($i['urgent_complaints'] > 0) {
            $recs[] = $this->rec('critical', 'Urgent Complaints Pending',
                "{$i['urgent_complaints']} urgent complaints are unresolved. Assign and triage immediately.",
                route('complaints.index'));
        }
        if ($i['overdue_projects'] > 0) {
            $recs[] = $this->rec('high', 'Overdue Projects',
                "{$i['overdue_projects']} project(s) past their end date. Review and replan.",
                route('projects.index'));
        }
        if ($i['overdue_tasks'] >= 5) {
            $recs[] = $this->rec('medium', 'Overdue Tasks',
                "{$i['overdue_tasks']} tasks are overdue. Rebalance workloads or extend deadlines.",
                route('projects.index'));
        }
        if ($i['over_allocated'] > 0) {
            $recs[] = $this->rec('high', 'Over-Allocated Resources',
                "{$i['over_allocated']} allocation(s) exceed their planned amount. Investigate cost drivers.",
                route('allocations.overview'));
        }
        if ($i['civic_rate'] < 20) {
            $recs[] = $this->rec('medium', 'Low Civic Participation',
                "Only {$i['civic_rate']}% of residents are marked as civically active. Launch an outreach announcement.",
                route('announcements.create'));
        }
        if ($i['volunteer_rate'] < 15) {
            $recs[] = $this->rec('low', 'Low Volunteer Rate',
                "Only {$i['volunteer_rate']}% of residents volunteer. Consider a community volunteer drive.",
                route('announcements.create'));
        }

        return $recs;
    }

    private function rec(string $severity, string $title, string $message, ?string $url = null): array
    {
        return compact('severity', 'title', 'message', 'url');
    }

    private function topComplaintCategories(int $limit = 5)
    {
        return Complaint::select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit($limit)
            ->pluck('total', 'category');
    }

    private function riskProjects(int $limit = 5)
    {
        return Project::whereNotNull('end_date')
            ->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->where('end_date', '<', now())->where('status', '!=', 'completed');
                })->orWhereColumn('actual_cost', '>', 'budget');
            })
            ->orderBy('end_date')
            ->limit($limit)
            ->get(['id', 'title', 'status', 'end_date', 'budget', 'actual_cost']);
    }
}
