<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AtkOrder extends Model
{
    protected $fillable = [
        'requested_by',
        'division_id',
        'status',
        'rejected_reason',
        'approved_at',
        'rejected_at',
        'finished_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(AtkOrderItem::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AtkOrderStatusHistory::class);
    }
}
