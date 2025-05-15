<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'scoring_method',
    ];

    public function questions()
    {
        return $this->hasMany(AuditQuestion::class);
    }
}
