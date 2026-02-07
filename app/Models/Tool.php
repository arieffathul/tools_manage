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
    ];

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function decrementQuantity($amount)
    {
        if ($this->quantity < $amount) {
            throw new \Exception('Insufficient stock');
        }

        $this->current_quantity -= $amount;
        $this->save();
    }
}
