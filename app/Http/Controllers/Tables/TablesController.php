<?php

namespace App\Http\Controllers\Tables;

use App\Http\Controllers\Controller;
use App\Models\Tables\TablesModel;
use Illuminate\Http\Request;

class TablesController extends Controller
{
    public static function addTeam(Request $request)
    {
        $rules = [
            "team_id" => "required|int",
            "competition_id" => "required|int",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $team = TablesModel::create($request->all());
        if (!$team) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        $table = self::getAllTeamsHandler($request->get('competition_id'));
        return response()->json(["success" => true, "table" => $table]);
    }

    public static function getTable(Request $request)
    {
        $rules = [
            "competition_id" => "required|int",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $table = self::getAllTeamsHandler($request->get('competition_id'));
        return response()->json(["success" => true, "table" => $table]);
    }

    private static function getAllTeamsHandler($id)
    {
        return TablesModel::where("competition_id", "=", $id)
            ->leftJoinSub("select id as tid,name,logo from flex_teams", "team", "team.tid", "=", "flex_tables.team_id")
            ->groupBy("flex_tables.id")
            ->orderBy("points", "desc")
            ->orderBy("gd", "desc")
            ->get();
    }

    public static function deleteFromTable(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "competition_id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        try {
            TablesModel::find($request->get("id"))->delete();
            $table = self::getAllTeamsHandler($request->get('competition_id'));
            return response()->json(["success" => true, "table" => $table]);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
    }

    public static function updateTable(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "games" => "required|int",
            "won" => "required|int",
            "drew" => "required|int",
            "lost" => "required|int",
            "gd" => "required|int",
            "points" => "required|int",
            "competition_id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $table = TablesModel::find($request->get("id"))->update($request->except("competition_id"));

        if (!$table) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        $table = self::getAllTeamsHandler($request->get('competition_id'));
        return response()->json(["success" => true, "table" => $table]);
    }
}
