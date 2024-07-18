<?php

namespace App\Models;

use App\Traits\CreateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory, CreateUuid;
    protected $guarded = [];

    protected $casts = [
        'images' => 'object'
    ];
    
    public function post (): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

}
