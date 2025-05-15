<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditQuestion extends Model
{
    protected $fillable = [
        'audit_template_id',
        'question_text',
        'type',
        'score',
        'options',
        'required',
    ];

    public function template()
    {
        return $this->belongsTo(AuditTemplate::class, 'audit_template_id');
    }
}
