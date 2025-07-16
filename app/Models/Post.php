<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'thumbnail', 'body', 'active', 'published_at', 'user_id', 'meta_title', 'meta_description'];

    protected $casts = ['published_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getFromattedDate()
    {
        return $this->published_at->format('F jS Y');
    }

    public function shortBody($words = 30)
    {
        return Str::words(strip_tags($this->body), 30);
    }

    public function getThumbnail()
    {
        if (str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        return '/storage/'.$this->thumbnail;
    }

    public function humanReadTime(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                $words = Str::wordCount(strip_tags($attributes['body']));
                $minutes = ceil($words / 200);

                return $minutes.' '.str('min')->plural($minutes).' , '.$words.' '.str('words')->plural($words);

            }
        );
    }
}
