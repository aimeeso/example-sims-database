<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['pack_id', 'name', 'description', 'image_path', 'release_date', 'eu_release_date', 'console_release_date'];

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    // protected $casts = [
    //     "release_date" => 'date:Y-m-d',
    //     "eu_release_date" => 'date:Y-m-d',
    //     "console_release_date" => 'date:Y-m-d',
    // ];

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
