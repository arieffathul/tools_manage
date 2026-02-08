<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    protected $table = 'return_details';

    protected $fillable = [
        'borrow_return_id',
        'tool_id',
        'quantity',
        'image',
        'locator',
    ];

    public function borrowReturn()
    {
        return $this->belongsTo(BorrowReturn::class, 'borrow_return_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
