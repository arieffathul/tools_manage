<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowDetail extends Model
{
    use HasFactory;

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
