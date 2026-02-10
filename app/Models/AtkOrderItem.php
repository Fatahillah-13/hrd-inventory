<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtkOrderItem extends Model
{
    protected $fillable = [
        'atk_order_id',
        'item_id',
        'qty_requested',
        'qty_ready',
        'qty_collected',
        'ready_at',
        'collected_at',
        'status',
    ];

    protected $casts = [
    'ready_at' => 'datetime',
    'collected_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(AtkOrder::class, 'atk_order_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
