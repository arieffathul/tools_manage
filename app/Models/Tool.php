<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'spec',
        'image',
        'quantity',
        'locator',
        'current_quantity',
        'current_locator',
        'last_audited_at',
        'deleted_at'
    ];
}
