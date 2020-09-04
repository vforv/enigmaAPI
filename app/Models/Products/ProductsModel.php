<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property integer $price
 * @property integer $discount_id
 * @property string $product_code
 * @property integer $amount
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
class ProductsModel extends Model
{
    protected $fillable = ["name", "description", "category_id", "price", "product_code", "color", "order", "discount", "material", "views", "special_offer", "discount_id", "amount"];
    protected $table = "flex_product";

    public function products()
    {
        return $this->hasMany(ProductsModel::class);
    }
}
