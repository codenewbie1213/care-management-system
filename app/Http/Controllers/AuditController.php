<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAuditRequest;
use App\Http\Requests\UpdateAuditRequest;
use App\Http\Requests\AssignAuditorsRequest;
use App\Http\Requests\RecordAuditResponseRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\AuditResponseResource;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        // Filtering and sorting can be added as needed
        $audits = Audit::with(['template', 'auditors', 'responses'])->get();
        return AuditResource::collection($audits);
    }

    public function store(StoreAuditRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $audit = Audit::create([
                'audit_template_id' => $data['audit_template_id'],
                'name' => $data['name'],
                'department' => $data['department'],
                'due_date' => $data['due_date'],
                'notes' => $data['notes'] ?? null,
                'created_by' => $request->user()->id,
            ]);
            $audit->auditors()->sync($data['auditor_ids']);
            $audit->load(['template', 'auditors', 'responses']);
            return (new AuditResource($audit))->response()->setStatusCode(201);
        });
    }

    public function show(Audit $audit)
    {
        $audit->load(['template', 'auditors', 'responses']);
        return new AuditResource($audit);
    }

    public function update(UpdateAuditRequest $request, Audit $audit)
    {
        $audit->update($request->validated());
        $audit->load(['template', 'auditors', 'responses']);
        return new AuditResource($audit);
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }

    public function assignAuditors(AssignAuditorsRequest $request, Audit $audit)
    {
        $audit->auditors()->sync($request->validated()['auditor_ids']);
        $audit->load(['template', 'auditors', 'responses']);
        return new AuditResource($audit);
    }

    public function recordResponse(RecordAuditResponseRequest $request, Audit $audit)
    {
        $responses = $request->validated()['responses'];
        $userId = $request->user()->id;
        $result = [];
        foreach ($responses as $resp) {
            $response = AuditResponse::updateOrCreate(
                [
                    'audit_id' => $audit->id,
                    'audit_question_id' => $resp['audit_question_id'],
                    'created_by' => $userId,
                ],
                [
                    'response' => $resp['response'] ?? null,
                    'score' => $resp['score'],
                    'notes' => $resp['notes'] ?? null,
                ]
            );
            $result[] = new AuditResponseResource($response);
        }
        return response()->json($result, 201);
    }
} 