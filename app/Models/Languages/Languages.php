<?php

namespace App\Models\Languages;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Player
 *
 * @property string $name
 * @property string $flag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereBirthPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereClubs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereStatement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereTitles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Player whereWeight($value)
 * @mixin \Eloquent
 */
class Languages extends Model
{
    protected $fillable = [
        'name', 'flag'
    ];
}
