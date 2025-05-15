<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\ActionPlan;
use App\Models\AuditTemplate;

class SearchController extends Controller
{
    public function audits(Request $request)
    {
        $query = Audit::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('department', 'like', "%$q%")
                    ->orWhere('status', 'like', "%$q%")
                    ->orWhere('notes', 'like', "%$q%")
                    ;
            });
        }
        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->input('date_to'));
        }
        $audits = $query->with(['template', 'auditors'])->orderByDesc('due_date')->get();
        return response()->json($audits);
    }

    public function actionPlans(Request $request)
    {
        $query = ActionPlan::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('action_item', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('status', 'like', "%$q%")
                    ->orWhere('progress', 'like', "%$q%")
                    ;
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('responsible_user_id')) {
            $query->where('responsible_user_id', $request->input('responsible_user_id'));
        }
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->input('due_date'));
        }
        $plans = $query->with(['audit', 'responsibleUser'])->orderByDesc('due_date')->get();
        return response()->json($plans);
    }

    public function auditTemplates(Request $request)
    {
        $query = AuditTemplate::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ;
            });
        }
        $templates = $query->withCount('questions')->orderBy('name')->get();
        return response()->json($templates);
    }
} 