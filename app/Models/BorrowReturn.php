<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowReturn extends Model
{
    protected $table = 'borrow_returns';

    protected $fillable = [
        'borrow_id',
        'returner_id',
        'job_reference',
        'notes',
    ];

    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    public function returner()
    {
        return $this->belongsTo(Engineer::class, 'returner_id');
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'borrow_return_id');
    }
}
