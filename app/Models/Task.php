<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tasks';
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
    ];

    function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
