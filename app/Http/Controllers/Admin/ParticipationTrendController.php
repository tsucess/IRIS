<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Project;
use App\Models\ResidentExtended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ParticipationTrendController extends Controller
{
    private const MONTHS_BACK_DEFAULT = 12;

    public function index(Request $request)
    {
        $months = (int) $request->get('months', self::MONTHS_BACK_DEFAULT);
        $months = max(3, min($months, 36));

        $data = $this->buildTrendData($months);

        return view('admin.analytics.participation', array_merge(
            ['months' => $months],
            $data
        ));
    }

    /**
     * JSON endpoint for AJAX refresh.
     */
    public function data(Request $request)
    {
        $months = (int) $request->get('months', self::MONTHS_BACK_DEFAULT);
        $months = max(3, min($months, 36));

        return response()->json($this->buildTrendData($months));
    }

    private function buildTrendData(int $months): array
    {
        $cacheKey = "participation_trend_{$months}";

        return Cache::remember($cacheKey, 300, function () use ($months) {
            $since = now()->subMonths($months - 1)->startOfMonth();
            $labels = $this->monthLabels($months);

            $civicSeries     = $this->monthlySeries('resident_extended', $since, $labels,
                fn ($q) => $q->where('civic_participation', true));
            $volunteerSeries = $this->monthlySeries('resident_extended', $since, $labels,
                fn ($q) => $q->where('volunteer_activities', true));
            $complaintSeries = $this->monthlySeries('complaints', $since, $labels);
            $projectMembers  = $this->monthlyJoinSeries('project_user', 'created_at', $since, $labels);

            $byCategory = Complaint::where('created_at', '>=', $since)
                ->select('category', DB::raw('COUNT(*) as total'))
                ->groupBy('category')->pluck('total', 'category');

            $totals = [
                'civic'      => array_sum($civicSeries),
                'volunteer'  => array_sum($volunteerSeries),
                'complaints' => array_sum($complaintSeries),
                'projects'   => array_sum($projectMembers),
            ];

            return compact(
                'labels', 'civicSeries', 'volunteerSeries',
                'complaintSeries', 'projectMembers', 'byCategory', 'totals'
            );
        });
    }

    private function monthLabels(int $months): array
    {
        $labels = [];
        $cursor = now()->subMonths($months - 1)->startOfMonth();
        for ($i = 0; $i < $months; $i++) {
            $labels[] = $cursor->copy()->addMonths($i)->format('Y-m');
        }

        return $labels;
    }

    /**
     * Returns array<label, count> of records per month from a table.
     */
    private function monthlySeries(string $table, $since, array $labels, ?callable $extra = null): array
    {
        $driver = DB::connection()->getDriverName();
        $expr   = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $query = DB::table($table)
            ->select(DB::raw("$expr as month"), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $since)
            ->groupBy('month');

        if ($extra) {
            $extra($query);
        }

        $raw = $query->pluck('total', 'month')->toArray();

        return $this->normaliseSeries($labels, $raw);
    }

    private function monthlyJoinSeries(string $table, string $col, $since, array $labels): array
    {
        $driver = DB::connection()->getDriverName();
        $expr   = $driver === 'sqlite'
            ? "strftime('%Y-%m', $col)"
            : "DATE_FORMAT($col, '%Y-%m')";

        $raw = DB::table($table)
            ->select(DB::raw("$expr as month"), DB::raw('COUNT(*) as total'))
            ->where($col, '>=', $since)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        return $this->normaliseSeries($labels, $raw);
    }

    private function normaliseSeries(array $labels, array $raw): array
    {
        $out = [];
        foreach ($labels as $label) {
            $out[] = (int) ($raw[$label] ?? 0);
        }

        return $out;
    }
}
