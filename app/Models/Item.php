<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'unit_id',
        'is_atk',
        'is_loanable',
        'responsible_division_id',
        'is_active',
    ];

    protected $casts = [
        'is_atk' => 'boolean',
        'is_loanable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function responsibleDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'responsible_division_id');
    }
}
