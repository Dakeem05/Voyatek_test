<?php

namespace App\Models;

use App\Traits\CreateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, CreateUuid;
    protected $guarded = [];

    protected $casts = [
        'images' => 'object'
    ];
    
    public function blog () :BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    public function likes (): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments (): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
