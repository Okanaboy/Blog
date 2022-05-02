<?php

namespace App\Models;

use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
