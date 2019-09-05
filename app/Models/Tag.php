<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'tag', 'title', 'subtitle','page_image','meta_description','reverse_direction'
    ];

    /**
     * 定义文章与标签质检多对多关联关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag_pivot');
    }

    public static function addNeededTags(array $tags)
    {
        if (0 === count($tags)) {
            return;
        }
        $found = static::whereIn('tag', $tags)->get()->pluck('tag')->all();

        foreach (array_diff($tags, $found) as $tag) {
            static::create([
               'tag' => $tags,
               'title' => $tags,
               'subtitle' => 'Subtitle for ' . $tag,
               'page_image' => '',
               'meta_description' => '',
               'reverse_direction' => false
            ]);
        }
    }
}
