<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\ActionPlan;
use App\Models\AuditTemplate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        // System-wide compliance rate (e.g., % of completed audits)
        $totalAudits = Audit::count();
        $completedAudits = Audit::where('status', 'completed')->count();
        $complianceRate = $totalAudits > 0 ? round(($completedAudits / $totalAudits) * 100, 2) : 0;

        // Trend analysis: audits completed per month (last 6 months)
        $trends = Audit::select(DB::raw('DATE_FORMAT(completed_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->whereNotNull('completed_at')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // Departmental comparison
        $departmentStats = Audit::select('department', DB::raw('COUNT(*) as total'), DB::raw('SUM(status = "completed") as completed'))
            ->groupBy('department')
            ->get();

        // Upcoming audits
        $upcomingAudits = Audit::where('status', '!=', 'completed')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Action plan status summary
        $actionPlanSummary = ActionPlan::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // User activity (last 7 days)
        $recentUsers = User::where('updated_at', '>=', now()->subDays(7))->get();

        // Audit template overview
        $templateStats = AuditTemplate::withCount('questions')->get();

        return response()->json([
            'compliance_rate' => $complianceRate,
            'trends' => $trends,
            'department_stats' => $departmentStats,
            'upcoming_audits' => $upcomingAudits,
            'action_plan_summary' => $actionPlanSummary,
            'recent_users' => $recentUsers,
            'template_stats' => $templateStats,
        ]);
    }

    public function manager(Request $request)
    {
        $user = $request->user();
        $department = $user->department ?? null;

        // Department compliance rate
        $totalAudits = Audit::where('department', $department)->count();
        $completedAudits = Audit::where('department', $department)->where('status', 'completed')->count();
        $complianceRate = $totalAudits > 0 ? round(($completedAudits / $totalAudits) * 100, 2) : 0;

        // Team performance (audits completed by team)
        $teamPerformance = Audit::where('department', $department)
            ->select('created_by', DB::raw('COUNT(*) as completed'))
            ->where('status', 'completed')
            ->groupBy('created_by')
            ->get();

        // Upcoming audits (department)
        $upcomingAudits = Audit::where('department', $department)
            ->where('status', '!=', 'completed')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Action plan status (department)
        $actionPlanSummary = ActionPlan::whereHas('audit', function ($q) use ($department) {
            $q->where('department', $department);
        })->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Audit findings (department)
        $auditFindings = Audit::where('department', $department)
            ->with('responses')
            ->orderBy('due_date', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'department_compliance_rate' => $complianceRate,
            'team_performance' => $teamPerformance,
            'upcoming_audits' => $upcomingAudits,
            'action_plan_summary' => $actionPlanSummary,
            'audit_findings' => $auditFindings,
        ]);
    }

    public function staff(Request $request)
    {
        $user = $request->user();

        // Assigned audits
        $assignedAudits = $user->audits()->with('template')->get();

        // Assigned action plans
        $assignedActionPlans = ActionPlan::where('responsible_user_id', $user->id)->get();

        // Personal progress (completed audits/action plans)
        $completedAudits = $user->audits()->where('status', 'completed')->count();
        $completedActionPlans = ActionPlan::where('responsible_user_id', $user->id)->where('status', 'completed')->count();

        // Audit history
        $auditHistory = $user->audits()->orderBy('due_date', 'desc')->get();

        return response()->json([
            'assigned_audits' => $assignedAudits,
            'assigned_action_plans' => $assignedActionPlans,
            'completed_audits' => $completedAudits,
            'completed_action_plans' => $completedActionPlans,
            'audit_history' => $auditHistory,
        ]);
    }
} 