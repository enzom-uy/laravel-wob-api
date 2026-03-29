<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob query()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @mixin \Eloquent
 */
class Wob extends Model
{

    protected $table = 'wob';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        "text",
        "source_url",
        "date",
    ];

    public function getLast() {}

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'wob_tags');
    }
}
