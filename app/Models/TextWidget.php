<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'image',
        'title',
        'content',
        'active',
    ];

    public static function getTitle(string $key)
    {

        $widget = \Illuminate\Support\Facades\Cache::get('text-widget-'.$key, function () use ($key) {
            return TextWidget::query()
                ->where('key', '=', $key)
                ->where('active', '=', 1)
                ->first();

        });
        if ($widget) {
            return $widget->title;
        }

        return '';
    }

    public static function getContent(string $key)
    {
        $widget = \Illuminate\Support\Facades\Cache::get('text-widget-'.$key, function () use ($key) {
            return TextWidget::query()
                ->where('key', '=', $key)
                ->where('active', '=', 1)
                ->first();

        });
        if ($widget) {
            return $widget->content;
        }

        return '';
    }
}
