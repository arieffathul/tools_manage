<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrow extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'engineer_id',
        'job_reference',
        'is_completed',
        'image',
    ];
    public function engineer()
    {
        return $this->belongsTo(Engineer::class);
    }

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }
}
