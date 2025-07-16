<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class UpvoteDownvote extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;

    }

    public function upVoteDownVote($upvote = true)
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
        if (! $user) {
            return $this->redirect('login');
        }
        if (! $user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }
        $model = \App\Models\UpvoteDownVote::where('post_id', '=', $this->post->id)
            ->where('user_id', $user->id)->first();

        if (! $model) {
            \App\Models\UpvoteDownVote::create([
                'is_upvote' => $upvote,
                'post_id' => $this->post->id,
                'user_id' => $user->id,
            ]);

            //  ! $user->posts()->dislikes()->exists() ? $user->posts()->dislikes()->attach() : null;

            return;
        }
        if ($upvote && $model->is_upvote || ! $upvote && ! $model->is_upvote) {
            $model->delete();

        } else {
            $model->is_upvote = $upvote;
            $model->save();

        }
    }

    public function render()
    {

        // counting total likes for a single post
        $upvotes = \App\Models\UpvoteDownVote::where('post_id', '=', $this->post->id)->where('is_upvote', '=', true)->count();
        $downvotes = \App\Models\UpvoteDownVote::where('post_id', '=', $this->post->id)->where('is_upvote', '=', false)->count();

        $has_up_vote = null; // this will be null if the user hasn't liked or disliked the post
        /** @var \App\Models\User $user */
        $user = request()->user();

        if ($user) {
            $model = \App\Models\UpvoteDownVote::where('post_id', '=', $this->post->id)
                ->where('user_id', '=', $user->id)
                ->first();

            if ($model) {
                $has_up_vote = (bool) $model->is_upvote;
            }
        }

        return view('livewire.upvote-downvote', compact('upvotes', 'downvotes', 'has_up_vote'));
    }
}
