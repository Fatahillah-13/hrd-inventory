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
        'status',
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
