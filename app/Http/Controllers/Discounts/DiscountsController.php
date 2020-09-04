<?php

namespace App\Http\Controllers\Discounts;

use App\Http\Controllers\Controller;
use App\Models\Discounts\DiscountsModel;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    public static function addDiscount(Request $request)
    {
        $rules = [
            "name" => "required",
            "discount" => "required",
            "date_from" => "required",
            "date_to" => "required"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $discount = DiscountsModel::create($request->all());

        if (!$discount) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        $discounts = DiscountsModel::orderBy("id", "desc")->get();
        return response()->json(["success" => true, "discounts" => $discounts]);
    }

    public static function getAllDiscounts()
    {
        $discounts = DiscountsModel::orderBy("id", "desc")->get();
        return response()->json(["success" => true, "discounts" => $discounts]);
    }

    public static function editDiscount(Request $request)
    {
        $rules = [
            "id" => "required",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $discount = DiscountsModel::find($request->get("id"));

        if (!$discount) {
            return response()->json(["success" => false, "message" => "Discount not found"]);
        }

        $discount->update($request->except("id"));

        $discounts = DiscountsModel::orderBy("id", "desc")->get();
        return response()->json(["success" => true, "discounts" => $discounts]);


    }

    public static function deleteDiscount(Request $request)
    {
        $rules = [
            "id" => "required",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $discount = DiscountsModel::find($request->get("id"));

        if (!$discount) {
            return response()->json(["success" => false, "message" => "Discount not found"]);
        }
        try {
            $discount->delete();
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
        }
        $discounts = DiscountsModel::orderBy("id", "desc")->get();
        return response()->json(["success" => true, "discounts" => $discounts]);
    }
}
