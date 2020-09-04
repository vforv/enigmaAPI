<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\StaffModel;
use App\Traits\HandleImageOptimization;
use App\Traits\HandleImageUpload;
use Illuminate\Http\Request;
use Validator;

class StaffController extends Controller
{
    use HandleImageUpload, HandleImageOptimization;

    public static function addStaff(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "position" => "required|string",
            "birth_date" => "required|string",
            "birth_place" => "required|string",
            "image" => "required|image",
            "nationality" => "required|string"
        ];
        $validator = Validator::make($request->all(), $rules);

        $image = [$request->file('image')];

        $uploaded = self::imageUploadHandler($image, "staff");

        $optimization = self::handleImageOptimization(250, true, $uploaded['images'], public_path($uploaded['path']));

        $uploadedImage = $uploaded["path"] . $uploaded['images'][0];

        $birthDate = date("Y-m-d", strtotime($request->get('birth_date')));
        $data = array_merge($request->except(["image", "birth_date"]), ["image" => $uploadedImage], ["birth_date" => $birthDate]);
        $staffMember = StaffModel::create($data);
        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }
        return response()->json(["success" => true, "staff" => $staffMember], 200);
    }

    public static function deleteStaff(Request $request)
    {
        $rules = [
            "id" => "required|int",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }

        $staffMember = StaffModel::find($request->get('id'));
        if (!$staffMember) {
            return response()->json(["success" => false, "message" => "Staff member could not be found"]);
        }
        $image = $staffMember['image'];

        try {
            $staffMember->delete();
        } catch (\Exception $e) {
        }

        $fileExtensions = [".jpg", ".webp", "-min.jpg", "-min.webp", "-mobile.jpg", "-mobile.webp"];
        $path_parts = pathinfo($image);

        $filename = $path_parts['filename'];
        $dirname = $path_parts['dirname'] . "/";

        foreach ($fileExtensions as $ext) {
            \Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
        }

        $staff = StaffModel::all();
        return response()->json(["success" => true, "staff" => $staff]);
    }

    public static function getAllStaff()
    {
        return response()->json(["success" => true, "staff" => StaffModel::all()], 200);
    }

    public static function getSingleStaff(Request $request)
    {
        $rules = [
            "id" => "required|int",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }

        $staffMember = StaffModel::find($request->get("id"));

        if (!$staffMember) {
            return response()->json(["success" => false, "message" => "Staff member could not be found"]);
        }

        return response()->json(["success" => true, "staff" => $staffMember]);
    }

    public static function updateStaff(Request $request)
    {
        $rules = [
            "name" => "required|string",
            "position" => "required|string",
            "birth_date" => "required|string",
            "birth_place" => "required|string",
            "nationality" => "required|string"
        ];

        $validator = Validator::make($request->all(), $rules);

        $staffMember = StaffModel::find($request->get("id"));
        $birthDate = date("Y-m-d", strtotime($request->get('birth_date')));
        $data = array_merge($request->except(["image", "birth_date"]), ["birth_date" => $birthDate]);
        if ($request->hasFile("image")) {
            $oldImage = $staffMember['image'];

            $fileExtensions = [".jpg", ".webp", "-min.jpg", "-min.webp", "-mobile.jpg", "-mobile.webp"];
            $path_parts = pathinfo($oldImage);
            $filename = $path_parts['filename'];
            $dirname = $path_parts['dirname'] . "/";

            foreach ($fileExtensions as $ext) {
                \Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
            }

            $image = [$request->file('image')];
            $uploaded = self::imageUploadHandler($image, "staff");
            $optimization = self::handleImageOptimization(250, true, $uploaded['images'], public_path($uploaded['path']));
            $uploadedImage = $uploaded["path"] . $uploaded['images'][0];
            $data = array_merge($data, ["image" => $uploadedImage]);
        }


        $staffMember->update($data);

        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }
        return response()->json(["success" => true, "staff" => $staffMember, "d" => $data], 200);
    }
}
