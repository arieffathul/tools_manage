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
        'issue',
        'action',
        'notes',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
