<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpvoteDownVote extends Model
{
    use HasFactory;

    protected $fillable = ['is_upvote', 'post_id', 'user_id'];
}
