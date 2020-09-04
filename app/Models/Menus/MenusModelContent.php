<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Player
 *
 * @property integer $id
 * @property string $name
 * @property integer $order
 * @property integer $level
 * @property integer $menu_id
 * @property string $language_id
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
class MenusModelContent extends Model
{
    protected $fillable = ["name", "order", "level", "parent_id", "menu_id", "link", "language_id", "file", "external", "placeholder"];
    protected $table = "flex_menus_items";
}


