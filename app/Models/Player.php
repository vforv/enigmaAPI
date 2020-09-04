<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Player
 *
 * @property int $id
 * @property string $name
 * @property int $number
 * @property int $position_id
 * @property string $birth_date
 * @property string $birth_place
 * @property int $height
 * @property int $weight
 * @property string $nationality
 * @property string $clubs
 * @property string $titles
 * @property string $statement
 * @property string $category
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
class Player extends Model
{
    protected $fillable = [
        'name', 'number', 'position', 'birth_date', 'birth_place', 'height', 'weight', 'nationality', "clubs", 'titles', 'statement', 'image', 'category'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = "flex_players";
    protected $dates = [];
}
