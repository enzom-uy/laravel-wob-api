<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob query()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property string $id
 * @property string $text
 * @property string|null $source_url
 * @property string|null $date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob whereSourceUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob whereUpdatedAt($value)
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
