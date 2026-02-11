<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokenTool extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tool_id',
        'reported_by',
        'handled_by',
        'quantity',
        'locator',
        'status',
        'image',
        'last_used',
        'issue',
        'action',
        'notes',
        'resolved_at',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function reporter()
    {
        return $this->belongsTo(Engineer::class, 'reported_by');
    }

    public function handler()
    {
        return $this->belongsTo(Engineer::class, 'handled_by');
    }
}
