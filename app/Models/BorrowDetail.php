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

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function decrementQuantity($amount)
    {
        // Kurangi quantity
        $this->quantity -= $amount;

        // Jika hasilnya minus, set ke 0
        if ($this->quantity < 0) {
            $this->quantity = 0;

            // Optional: Log warning
            // \Log::warning("Tool {$this->name} (ID: {$this->id}) quantity set to 0 after decrement. Was negative.");
        }

        if ($this->quantity <= 0) {
            $this->is_complete = 1;
            $this->completed_at = now();
        }

        $this->save();

        return $this->quantity; // Return final value
    }

    public function incrementQuantity($amount)
    {
        // Tambah quantity
        $this->quantity += $amount;

        $this->save();

        return $this->quantity; // Return final value
    }
}
