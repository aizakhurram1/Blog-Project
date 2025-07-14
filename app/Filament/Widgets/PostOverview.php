<?php

namespace App\Filament\Widgets;

use App\Models\PostView;
use App\Models\UpvoteDownVote;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class PostOverview extends Widget
{
    protected int|string|array $columnSpan = 3;

    public ?Model $record = null;

    protected function getViewData(): array
    {
        if (! $this->record) {
            return [
                'view_count' => 0,
                'upvotes' => 0,
                'downvotes' => 0,
            ];
        }

        return [
            'view_count' => PostView::where('post_id', $this->record->id)->count(),
            'upvotes' => UpvoteDownVote::where('post_id', $this->record->id)->where('is_upvote', 1)->count(),
            'downvotes' => UpvoteDownVote::where('post_id', $this->record->id)->where('is_upvote', 0)->count(),
        ];
    }

    protected static string $view = 'filament.widgets.post-overview';
}
