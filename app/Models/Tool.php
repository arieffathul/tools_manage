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

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function brokenTools()
    {
        return $this->hasMany(BrokenTool::class);
    }

    public function decrementQuantity($amount)
    {
        // Kurangi current_quantity
        $this->current_quantity -= $amount;

        // Jika hasilnya minus, set ke 0
        if ($this->current_quantity < 0) {
            $this->current_quantity = 0;

            // Optional: Log warning
            // \Log::warning("Tool {$this->name} (ID: {$this->id}) current_quantity set to 0 after decrement. Was negative.");
        }

        $this->save();

        return $this->current_quantity; // Return final value
    }

    public function incrementQuantity($amount)
    {
        // Tambah current_quantity
        $this->current_quantity += $amount;

        // Jika melebihi quantity (stok maksimal), set ke quantity
        if ($this->current_quantity > $this->quantity) {
            $this->current_quantity = $this->quantity;

            // Optional: Log warning
            // \Log::warning("Tool {$this->name} (ID: {$this->id}) current_quantity capped at max stock ({$this->quantity}) after increment.");
        }

        $this->save();

        return $this->current_quantity; // Return final value
    }
}
