<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'corporate_id',
        'vendor_id',
        'invoice_number',
        'quantity',
        'rate',
        'amount',
        'status',
        'issue_date',
        'due_date',
        'payment_terms',
        'description',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function calculateAmount(): float|int
    {
        return $this->quantity * $this->rate;
    }

    public function isOverdue(): bool
    {
        return $this->status === 'OPEN' && now()->gt($this->due_date);
    }
}
