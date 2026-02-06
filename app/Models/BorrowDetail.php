<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'borrow_id',
        'tool_id',
        'quantity',
    ];
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
    
}
