<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditTemplate;
use App\Models\AuditQuestion;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAuditTemplateRequest;
use App\Http\Requests\UpdateAuditTemplateRequest;
use App\Http\Requests\StoreAuditQuestionRequest;
use App\Http\Requests\UpdateAuditQuestionRequest;
use App\Http\Resources\AuditTemplateResource;
use App\Http\Resources\AuditQuestionResource;

class AuditTemplateController extends Controller
{
    public function index()
    {
        $templates = AuditTemplate::with('questions')->get();
        return AuditTemplateResource::collection($templates);
    }

    public function store(StoreAuditTemplateRequest $request)
    {
        $template = AuditTemplate::create($request->validated());
        $template->load('questions');
        return (new AuditTemplateResource($template))->response()->setStatusCode(201);
    }

    public function show(AuditTemplate $auditTemplate)
    {
        $auditTemplate->load('questions');
        return new AuditTemplateResource($auditTemplate);
    }

    public function update(UpdateAuditTemplateRequest $request, AuditTemplate $auditTemplate)
    {
        $auditTemplate->update($request->validated());
        $auditTemplate->load('questions');
        return new AuditTemplateResource($auditTemplate);
    }

    public function destroy(AuditTemplate $auditTemplate)
    {
        $auditTemplate->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }

    // --- Question Management ---
    public function addQuestion(StoreAuditQuestionRequest $request, AuditTemplate $auditTemplate)
    {
        $question = $auditTemplate->questions()->create($request->validated());
        return (new AuditQuestionResource($question))->response()->setStatusCode(201);
    }

    public function updateQuestion(UpdateAuditQuestionRequest $request, AuditTemplate $auditTemplate, AuditQuestion $question)
    {
        if ($question->audit_template_id !== $auditTemplate->id) {
            return response()->json(['error' => 'Question does not belong to this template.'], 403);
        }
        $question->update($request->validated());
        return new AuditQuestionResource($question);
    }

    public function deleteQuestion(AuditTemplate $auditTemplate, AuditQuestion $question)
    {
        if ($question->audit_template_id !== $auditTemplate->id) {
            return response()->json(['error' => 'Question does not belong to this template.'], 403);
        }
        $question->delete();
        return response()->json(['message' => 'Question deleted.']);
    }
} 