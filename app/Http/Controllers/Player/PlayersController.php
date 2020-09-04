<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Traits\HandleImageOptimization;
use App\Traits\HandleImageUpload;
use Illuminate\Http\Request;
use Validator;

class PlayersController extends Controller
{
    use HandleImageUpload, HandleImageOptimization;

    public static function addNewPlayer(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'number' => 'required|int',
            'position' => 'required|string',
            'birth_date' => 'required|string',
            'birth_place' => 'required|string',
            'height' => 'required|int',
            'weight' => 'required|int',
            'nationality' => 'required|string',
            'clubs' => 'required|string',
            'category' => 'required|int'
        ];
        $validator = Validator::make($request->all(), $rules);

        $uploadedImage = null;
        if ($request->hasFile("image")) {
            $image = [$request->file('image')];

            $uploaded = self::imageUploadHandler($image, "players");

            $optimization = self::handleImageOptimization(700, true, $uploaded['images'], public_path($uploaded['path']));
            $uploadedImage = $uploaded["path"] . $uploaded['images'][0];
        }


        $birthDate = date("Y-m-d", strtotime($request->get('birth_date')));
        $data = array_merge($request->except(["image", "birth_date"]), ["image" => $uploadedImage], ["birth_date" => $birthDate]);
        $player = Player::create($data);

        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }
        return response()->json(["success" => true, "player" => $player], 200);
    }

    public static function getAllPlayers(Request $request)
    {
        $players = Player::where("category", "=", $request->get("category"))->get();
        return response()->json(["success" => true, "players" => $players], 200);
    }

    public static function getPlayer(Request $request)
    {
        $rules = [
            'id' => 'required|int',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }

        $player = Player::find($request->get('id'));
        if (!$player) {
            return response()->json(["success" => false, "message" => "Player not found."], 404);

        }
        return response()->json(["success" => true, "player" => $player], 200);
    }

    public static function deletePlayer(Request $request)
    {
        $rules = [
            'id' => 'required|int',
        ];

        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $player = Player::find($request->get('id'));
        if (!$player) {
            return response()->json(["success" => false, "message" => "Team could not be found"]);
        }
        $image = $player['image'];

        try {
            $player->delete();
        } catch (\Exception $e) {
        }

        $category = $player['category'];

        if ($image) {
            $fileExtensions = [".jpg", ".webp", "-min.jpg", "-min.webp", "-mobile.jpg", "-mobile.webp"];
            $path_parts = pathinfo($image);

            $filename = $path_parts['filename'];
            $dirname = $path_parts['dirname'] . "/";

            foreach ($fileExtensions as $ext) {
                \Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
            }
        }

        $players = Player::where("category", "=", $category)->get();
        return response()->json(["success" => true, "players" => $players]);
    }

    public static function updatePlayer(Request $request)
    {
        $rules = [
            'id' => 'required|int',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(["success" => false, "errors" => $validator->errors()], 400);
        }
        $player = Player::find($request->get('id'));
        $data = [];

        if ($request->hasFile("image")) {
            $oldImage = $player['image'];

            $fileExtensions = [".jpg", ".webp", "-min.jpg", "-min.webp", "-mobile.jpg", "-mobile.webp"];
            $path_parts = pathinfo($oldImage);
            $filename = $path_parts['filename'];
            $dirname = $path_parts['dirname'] . "/";

            foreach ($fileExtensions as $ext) {
                \Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
            }

            $image = [$request->file('image')];
            $uploaded = self::imageUploadHandler($image, "players");
            $optimization = self::handleImageOptimization(700, true, $uploaded['images'], public_path($uploaded['path']));
            $uploadedImage = $uploaded["path"] . $uploaded['images'][0];
            $data = array_merge($data, ["image" => $uploadedImage]);
        }
        $birthDate = date("Y-m-d", strtotime($request->get('birth_date')));
        $data = array_merge($data, $request->except(["id", "image"]), ["birth_date" => $birthDate]);

        $player->update($data);
        return response()->json(["success" => true, "player" => $player], 200);

    }
}
