<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductCategoryModel extends Model
{
    protected $fillable = ["id", "name", "description", "parent_id", "level", "order", "slug", "childrenCategories", "discount_id"];
    protected $table = "flex_product_category";

    public function categories()
    {
        return $this->hasMany(ProductCategoryModel::class, "parent_id");
    }

    public function childrenCategories()
    {
        return $this->hasMany(ProductCategoryModel::class, "parent_id")->with("childrenCategories");
    }
}
