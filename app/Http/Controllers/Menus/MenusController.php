<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use App\Models\Menus\MenusModel;
use App\Models\Menus\MenusModelContent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenusController extends Controller
{
    public static function addMenu(Request $request)
    {
        $rules = [
            "name" => "required|string|unique:flex_menus",
            "position" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $menu = MenusModel::create($request->all());

        if (!$menu) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "menus" => MenusModel::all()]);
    }

    public static function editMenu(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "name" => ["required", Rule::unique('flex_menus')->ignore($request->get("id"))],
            "position" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $menuItem = MenusModel::find($request->get("id"));

        if (!$menuItem) {
            return response()->json(["success" => false, "message" => "Menu not found"]);
        }

        $menuItem->update($request->except("id"));

        $menuItems = MenusModel::all();
        return response()->json(["success" => true, "menus" => $menuItems]);

    }

    public static function getAllMenus()
    {
        $menus = MenusModel::all();

        if (!$menus) {
            return response()->json(["success" => false, "message" => "No menus found"]);
        }
        return response()->json(["success" => true, "menus" => $menus]);
    }

    public static function deleteMenu(Request $request)
    {
        $rules = [
            "id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $menu = MenusModel::find($request->get("id"));
        try {
            $menu->delete();
            return response()->json(["success" => true, "menus" => MenusModel::all()]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
        }
    }

    public static function addMenuItem(Request $request)
    {
        $rules = [
            "name" => "required|string|unique:flex_menus_items",
            "order" => "required|int",
            "level" => "required|int",
            "menu_id" => "required|int",
            "language_id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $menuItem = MenusModelContent::create($request->all());

        if (!$menuItem) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        $menuItems = MenusModelContent::where("menu_id", "=", $request->get("menu_id"))->get();
        $items = self::build_tree($menuItems);
        return response()->json(["success" => true, "items" => $items]);
    }

    public static function editMenuItem(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "name" => ["required", Rule::unique('flex_menus_items')->ignore($request->get("id"))],
            "order" => "required|int",
            "level" => "required|int",
            "menu_id" => "required|int",
            "language_id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $menuItem = MenusModelContent::find($request->get("id"));

        if (!$menuItem) {
            return response()->json(["success" => false, "message" => "Menu item not found"]);
        }

        $menuItem->update($request->except("id"));

        $menuItems = MenusModelContent::where("menu_id", "=", $request->get("menu_id"))->get();
        $items = self::build_tree($menuItems);
        return response()->json(["success" => true, "items" => $items]);
    }

    public static function getAllMenuItems(Request $request)
    {
        $rules = [
            "id" => "required|int",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $items = self::fetchMenuItems($request->get("id"));
        if (!$items) {
            return response()->json(["success" => false, "message" => "Items not found"]);
        }
        $menu = MenusModel::find($request->get("id"));
        return response()->json(["success" => true, "menu" => $menu, "items" => $items]);
    }

    private static function fetchMenuItems($id)
    {
        $menuItems = MenusModelContent::where("menu_id", "=", $id)->orderBy("order")->get();
        return self::build_tree($menuItems);
    }

    public static function deleteMenuItem(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "menu_id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $menuItems = MenusModelContent::find($request->get("id"));

        try {
            $menuItems->delete();
            return response()->json(["success" => true, "items" => self::fetchMenuItems($request->get("menu_id"))]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
        }
    }

    private static function build_tree(&$items, $parent = 0)
    {
        $tmp_array = [];
        foreach ($items as $item) {
            if ($item->parent_id == $parent) {
                $item->children = self::build_tree($items, $item->id);
                $tmp_array[] = $item;
            }
        }
        return $tmp_array;
    }


    public static function getAllParentMenus(Request $request)
    {
        $rules = [
            "id" => "required|int",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $menuItems = MenusModelContent::where("menu_id", "=", $request->get("id"))->orderBy("parent_id")->orderBy("level")->orderBy("order")->get();
        if (!$menuItems) {
            return response()->json(["success" => false, "message" => "Items not found"]);
        }
        return response()->json(["success" => true, "items" => $menuItems]);
    }

    public static function sortMenus(Request $request)
    {
        $menus = $request->get("menus");
        $counter = 0;
        foreach ($menus as $menu) {
            $m = MenusModelContent::where('id', '=', $menu['id'])->update(['order' => $counter]);
            $counter++;
        }
        return response()->json(["success" => true, "menus" => $menus]);
    }
}


