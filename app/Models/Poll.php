<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
    ];

    public function answers(): HasMany
    {
        return  $this->hasMany(Answer::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
