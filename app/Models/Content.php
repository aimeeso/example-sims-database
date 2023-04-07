<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
    use HasFactory;

    protected $guards = [];

    protected $casts = [
        "release_date" => 'datetime:Y-m-d',
        "eu_release_date" => 'datetime:Y-m-d',
        "console_release_date" => 'datetime:Y-m-d',
    ];

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }

    public function scopeFilterPackId($query, $value)
    {
        if (empty($value)) return $query;

        return $query->where('pack_id', $value); //exact pack id
    }
}
