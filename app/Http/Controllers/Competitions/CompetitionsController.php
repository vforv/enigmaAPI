<?php

namespace App\Http\Controllers\Competitions;

use App\Http\Controllers\Controller;
use App\Models\Competitions\CompetitonsModel;
use App\Traits\HandleImageOptimization;
use App\Traits\HandleImageUpload;
use Illuminate\Http\Request;

class CompetitionsController extends Controller
{
    use HandleImageUpload, HandleImageOptimization;

    public static function addCompetition(Request $request)
    {
        $rules = [
            "name" => "required|string|unique:flex_competitions",
        ];

        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $data = [];
        if ($request->hasFile("logo")) {
            $images = [$request->file('logo')];
            $uploaded = self::imageUploadHandler($images, "competitions");
            $optimization = self::handleImageOptimization(120, false, $uploaded['images'], public_path($uploaded['path']));
            $data = array_merge($data, ["logo" => $uploaded['path'] . $uploaded['images'][0]]);
        }

        $data = array_merge($data, ["name" => $request->get('name')]);

        $competition = CompetitonsModel::create($data)->latest()->first();

        if (!$competition) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "message" => "Competition created successfully", "competition" => $competition]);
    }

    public static function getAllCompetitions()
    {
        return response()->json(["success" => true, "competitions" => CompetitonsModel::all()]);
    }

    public static function deleteCompetition(Request $request)
    {
        $rules = [
            "id" => "required|int",
        ];
        try {
            $competition = CompetitonsModel::find($request->get('id'))->delete();
            return response()->json(["success" => true, "competitions" => CompetitonsModel::all()]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Something went wrong."]);
        }
    }

    public static function editCompetition(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "name" => "string|unique:flex_competitions,id," . $request->get('id'),
        ];

        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $data = [];
        if ($request->hasFile("logo")) {
            $images = [$request->file('logo')];
            $uploaded = self::imageUploadHandler($images, "competitions");
            $optimization = self::handleImageOptimization(120, false, $uploaded['images'], public_path($uploaded['path']));
            $data = array_merge($data, ["logo" => $uploaded['path'] . $uploaded['images'][0]]);
        }

        $data = array_merge($data, ["name" => $request->get('name')]);

        $competition = CompetitonsModel::find($request->get("id"))->update($data);

        if (!$competition) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "message" => "Competition edited successfully", "competitions" => CompetitonsModel::all()]);
    }
}
