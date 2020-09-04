<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\PlayerCategory;
use Illuminate\Http\Request;

class PlayerCategoryController extends Controller
{
    public static function addCategory(Request $request)
    {
        $rules = [
            "name" => "required|string|unique:flex_players_category"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $category = PlayerCategory::create($request->all());

        if (!$category) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "categories" => PlayerCategory::all()]);
    }

    public static function deleteCategory(Request $request)
    {
        $rules = [
            "id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $category = PlayerCategory::find($request->get('id'));
        try {
            $category->delete();
            return response()->json(["success" => true, "categories" => PlayerCategory::all()]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
        }
    }

    public static function getAllCategories()
    {
        return response()->json(["success" => true, "categories" => PlayerCategory::all()]);
    }

    public static function editCategory(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "name" => "required|string|unique:flex_players_category"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $category = PlayerCategory::find($request->get("id"))->update(["name" => $request->get("name")]);

        if (!$category) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "categories" => PlayerCategory::all()]);
    }
}
