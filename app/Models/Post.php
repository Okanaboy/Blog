<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Image;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_posts');
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::class, 'comment_posts');
    }
}
