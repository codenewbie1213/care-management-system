<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionPlan extends Model
{
    protected $fillable = [
        'audit_id',
        'action_item',
        'description',
        'status',
        'due_date',
        'responsible_user_id',
        'progress',
        'completion_evidence',
        'created_by',
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
