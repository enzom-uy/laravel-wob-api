<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $wob_id
 * @property string $tag_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wob_Tags whereWobId($value)
 * @mixin \Eloquent
 */
class Wob_Tags extends Model
{
    protected $table = 'wob_tags';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "wob_id",
        "tag_id"
    ];
}
