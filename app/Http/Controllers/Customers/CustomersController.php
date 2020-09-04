<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customers\CustomersModel;
use App\Traits\Mail\MailingTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    use MailingTraits;

    public static function register(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "email" => "required|email|unique:flex_customers",
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
            "phone" => "required",
            "address" => "required",
            "city" => "required",
            "type" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $request['token'] = \Str::random(64);
        $request["password"] = md5($request->get("password"));

        $customer = CustomersModel::create($request->all());

        if (!$customer) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }

        $name = $request->get("name");
        $link = env("ADMIN_URL") . "/confirm/" . $request->get("token");
        $email = $request->get("email");
        self::confirmRegistration(["name" => $name, "link" => $link, "email" => $email]);

        return response()->json(["success" => true, "message" => "Confirmation mail sent"]);
    }

    public static function confirmUser($token)
    {
        $customer = CustomersModel::where("token", "=", $token)->first();

        if (!$customer) {
            return redirect(env("APP_URL") . "/wrong-credentials");
        }

        $customer->token = null;
        $customer->active = true;
        $customer->save();
        return redirect(env("APP_URL") . "/account-confirmed");

    }

    public static function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $validation = ValidateHttpRequest($rules, $request->all(), true);

        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $user = CustomersModel::where("email", "=", $request->get("email"))
            ->where("password", "=", md5($request->get("password")))
            ->where("active", "=", 1)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong login credentials.',
                "req" => $request->all(),
                "pw" => md5($request->get("password"))
            ], 200);
        }


        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public static function handleDiscount(Request $request)
    {
        $user = CustomersModel::find($request->get("id"));
        if (!$user) {
            return response()->json(["success" => false, "message" => "User not found"]);
        }
        $user->discount_id = $request->get("discount_id");
        $user->save();
        return response()->json(["success" => true, "customers" => CustomersModel::where("type", "=", 1)->get()]);
    }

    public static function getAllCustomers()
    {
        return response()->json(["success" => true, "customers" => CustomersModel::where("type", "=", 1)->get()]);
    }
}
