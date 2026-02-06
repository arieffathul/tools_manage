<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Engineer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'shift',
        'status',
        'inactived_at',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
