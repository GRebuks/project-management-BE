<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'color',
    ];

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_workspace');
    }
}
