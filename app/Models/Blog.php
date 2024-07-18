<?php

namespace App\Models;

use App\Traits\CreateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrManyThrough;

class Blog extends Model
{
    use HasFactory, CreateUuid;
    protected $guarded = [];
    
    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function posts () :HasMany
    {
        return $this->hasMany(Post::class);
    }

    Public function likes (): HasOneOrManyThrough
    {
        return $this->hasOneOrManyThrough(Post::class, Like::class);
    }

    Public function comments (): HasOneOrManyThrough
    {
        return $this->hasOneOrManyThrough(Post::class, Comment::class);
    }
}
