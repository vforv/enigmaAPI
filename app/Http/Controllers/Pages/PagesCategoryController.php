<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Pages\PagesCategoryModel;
use Illuminate\Http\Request;

class PagesCategoryController extends Controller
{
    public static function addCategory(Request $request)
    {
        $rules = [
            "name" => "required|string|unique:flex_pages_category",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $category = PagesCategoryModel::create($request->all());

        if (!$category) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "categories" => PagesCategoryModel::all()]);
    }

    public static function deleteCategory(Request $request)
    {
        $rules = [
            "id" => "required|int|gt:2",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        try {
            $category = PagesCategoryModel::find($request->get("id"))->delete();
            return response()->json(["success" => true, "categories" => PagesCategoryModel::all()]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
        }
    }

    public static function allCategories()
    {
        return response()->json(["success" => true, "categories" => PagesCategoryModel::all()]);
    }

    public static function editCategory(Request $request)
    {
        $rules = [
            "id" => "required|int|gt:2",
            "name" => "required|string|unique:flex_pages_category"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $category = PagesCategoryModel::find($request->get("id"))->update(["name" => $request->get("name")]);

        if (!$category) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "categories" => PagesCategoryModel::all()]);
    }
}
