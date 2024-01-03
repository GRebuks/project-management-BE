<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'color',
        'board_column_id',
        'order',
        'due_date',
        'completed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->order = self::where('board_column_id', $model->board_column_id)->max('order') + 1;
        });
    }

    public function boardColumn(): BelongsTo
    {
        return $this->belongsTo(BoardColumn::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
