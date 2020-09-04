<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team\Team;
use App\Traits\HandleImageOptimization;
use App\Traits\HandleImageUpload;
use Illuminate\Http\Request;
use Storage;

class TeamController extends Controller
{
    use HandleImageUpload, HandleImageOptimization;

    public static function addTeam(Request $request)
    {
        $rules = [
            'name' => 'required|string|unique:flex_teams',
            'location' => 'required|string'
        ];

        $images = [$request->file('logo')];

        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $create = ["name" => $request->get('name'), "location" => $request->get('location')];
        if ($request->hasFile('logo')) {
            $images = [$request->file('logo')];
            $uploaded = self::imageUploadHandler($images, "teams");
            $optimization = self::handleImageOptimization(150, false, $uploaded['images'], public_path($uploaded['path']));
            $logo = ["logo" => $uploaded['path'] . $uploaded['images'][0]];
            $create = array_merge($create, $logo);
        }
        $team = Team::create($create);
        if ($team) {
            return response()->json(["success" => true, "message" => "Team successfully created.", "team" => $team]);
        }

        return response()->json(["success" => false, "message" => "Something went wrong, please try again later."]);
    }

    public static function editTeam(Request $request)
    {
        $rules = [
            'id' => 'required|int',
        ];


        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $update = ["name" => $request->get('name'), "location" => $request->get('location')];
        if ($request->hasFile('logo')) {
            $images = [$request->file('logo')];
            $uploaded = self::imageUploadHandler($images, "teams");
            $optimization = self::handleImageOptimization(150, false, $uploaded['images'], public_path($uploaded['path']));
            $logo = ["logo" => $uploaded['path'] . $uploaded['images'][0]];
            $update = array_merge($update, $logo);
        }

        $team = Team::find($request->get('id'))->update($update);

        if ($team) {
            return response()->json(["success" => true, "message" => "Team successfully updated.", "up" => $update]);
        }

        return response()->json(["success" => false, "message" => "Something went wrong, please try again later."]);
    }

    public static function getAllTeams()
    {
        return response()->json(["success" => true, "teams" => Team::all()]);
    }

    public static function deleteTeam(Request $request)
    {
        $rules = [
            'id' => "required|int"
        ];

        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $team = Team::find($request->get('id'));
        if (!$team) {
            return response()->json(["success" => false, "message" => "Team could not be found"]);
        }
        $image = $team['logo'];

        try {
            $team->delete();
        } catch (\Exception $e) {
        }

        $fileExtensions = [".png", ".webp", "-min.png", "-min.webp", "-mobile.png", "-mobile.webp"];

        $path_parts = pathinfo($image);
        $filename = $path_parts['filename'];
        $dirname = $path_parts['dirname'] . "/";

        foreach ($fileExtensions as $ext) {
            Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
        }

        $teams = Team::all();
        return response()->json(["success" => true, "teams" => $teams]);

    }
}
