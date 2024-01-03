<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardColumn extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'order',
        'board_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->order = self::where('board_id', $model->board_id)->max('order') + 1;
        });
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
