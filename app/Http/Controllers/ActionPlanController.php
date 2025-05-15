<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ActionPlan;
use Illuminate\Http\Request;
use App\Http\Requests\StoreActionPlanRequest;
use App\Http\Requests\UpdateActionPlanRequest;
use App\Http\Resources\ActionPlanResource;
use Illuminate\Support\Facades\Storage;

class ActionPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = ActionPlan::query();
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->input('due_date'));
        }
        if ($request->filled('responsible_user_id')) {
            $query->where('responsible_user_id', $request->input('responsible_user_id'));
        }
        if ($request->filled('audit_id')) {
            $query->where('audit_id', $request->input('audit_id'));
        }
        $plans = $query->with(['audit', 'responsibleUser', 'creator'])->orderByDesc('due_date')->get();
        return ActionPlanResource::collection($plans);
    }

    public function store(StoreActionPlanRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('completion_evidence')) {
            $data['completion_evidence'] = $request->file('completion_evidence')->store('action_plans', 'public');
        }
        $data['created_by'] = $request->user()->id;
        $plan = ActionPlan::create($data);
        $plan->load(['audit', 'responsibleUser', 'creator']);
        return (new ActionPlanResource($plan))->response()->setStatusCode(201);
    }

    public function show(ActionPlan $actionPlan)
    {
        $actionPlan->load(['audit', 'responsibleUser', 'creator']);
        return new ActionPlanResource($actionPlan);
    }

    public function update(UpdateActionPlanRequest $request, ActionPlan $actionPlan)
    {
        $data = $request->validated();
        if ($request->hasFile('completion_evidence')) {
            if ($actionPlan->completion_evidence) {
                Storage::disk('public')->delete($actionPlan->completion_evidence);
            }
            $data['completion_evidence'] = $request->file('completion_evidence')->store('action_plans', 'public');
        }
        $actionPlan->update($data);
        $actionPlan->load(['audit', 'responsibleUser', 'creator']);
        return new ActionPlanResource($actionPlan);
    }

    public function destroy(ActionPlan $actionPlan)
    {
        if ($actionPlan->completion_evidence) {
            Storage::disk('public')->delete($actionPlan->completion_evidence);
        }
        $actionPlan->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }
} 