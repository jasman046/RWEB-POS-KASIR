<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'card_id',
        'transaction_code',
        'description',
        'type',
        'amount',
        'transaction_date',
        'category',
        'receipt_path',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    /**
     * Relasi ke Card
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Format nominal
     */
    public function getFormattedAmountAttribute()
    {
        $prefix = $this->category === 'Income'
            ? '+ Rp '
            : '- Rp ';

        return $prefix . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Format tanggal
     */
    public function getFormattedDateAttribute()
    {
        return $this->transaction_date->format('d M, h:i A');
    }
}