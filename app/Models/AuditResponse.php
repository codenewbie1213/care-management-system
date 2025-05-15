<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditResponse extends Model
{
    protected $fillable = [
        'audit_id',
        'audit_question_id',
        'response',
        'score',
        'notes',
        'created_by',
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function question()
    {
        return $this->belongsTo(AuditQuestion::class, 'audit_question_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
