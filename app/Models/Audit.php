<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Audit extends Model
{
    protected $fillable = [
        'audit_template_id',
        'name',
        'department',
        'status',
        'due_date',
        'completed_at',
        'notes',
        'created_by',
    ];

    public function template()
    {
        return $this->belongsTo(AuditTemplate::class, 'audit_template_id');
    }

    public function auditors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'audit_user');
    }

    public function responses()
    {
        return $this->hasMany(AuditResponse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
