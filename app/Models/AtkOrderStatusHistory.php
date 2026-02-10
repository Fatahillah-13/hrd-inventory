<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtkOrderStatusHistory extends Model
{
    protected $fillable = [
        'atk_order_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(AtkOrder::class, 'atk_order_id');
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
