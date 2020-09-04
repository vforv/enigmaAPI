<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Games\GamesModel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public static function addGame(Request $request)
    {
        $rules = [
            "host_id" => "required|int",
            "guest_id" => "required|int",
            "competition_id" => "required|int",
            "date" => "required",
            "location" => "required|string"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $request->merge(["date" => date('Y-m-d H:i', strtotime($request->get('date')))]);
        $game = GamesModel::create($request->all());

        if (!$game) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "message" => "Game successfully added", "date" => $request->get('date')]);

    }

    public static function editGame(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "host_id" => "required|int",
            "guest_id" => "required|int",
            "competition_id" => "required|int",
            "date" => "required",
            "location" => "required|string"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $request->merge(["date" => date('Y-m-d H:i', strtotime($request->get('date')))]);

        $game = GamesModel::find($request->get('id'))->update($request->except(['id']));

        if (!$game) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "message" => "Game successfully updated", "date" => $request->get('date')]);

    }

    public static function getAllGames()
    {
        $games = GamesModel::selectRaw('flex_games.id,flex_games.location ,flex_games.date,flex_games.host_goals,flex_games.guest_goals, team.name as host_name, team.logo as host_logo, team.id as host_id,team2.name as guest_name,team2.logo as guest_logo, competition.name as competition_name,competition.logo as competition_logo')
            ->leftJoin('flex_teams as team', 'flex_games.host_id', '=', 'team.id')
            ->leftJoin('flex_teams as team2', 'flex_games.guest_id', '=', 'team2.id')
            ->leftJoin('flex_competitions as competition', 'competition.id', '=', 'flex_games.competition_id')
            ->orderBy('flex_games.date')
            ->groupBy('flex_games.id')
            ->get();
        return response()->json(["success" => true, "games" => $games]);
    }

    public static function getGame(Request $request)
    {
        $rules = [
            "id" => "required|int",

        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $game = GamesModel::where('flex_games.id', '=', $request->get('id'))->selectRaw('flex_games.id,flex_games.location, flex_games.date,flex_games.host_goals,flex_games.guest_goals, team.id as host_id,team.name as host_name, team.logo as host_logo,team2.id as guest_id, team2.name as guest_name,team2.logo as guest_logo, competition.name as competition_name, competition.id as competition_id, competition.logo as competition_logo')
            ->leftJoin('flex_teams as team', 'flex_games.host_id', '=', 'team.id')
            ->leftJoin('flex_teams as team2', 'flex_games.guest_id', '=', 'team2.id')
            ->leftJoin('flex_competitions as competition', 'competition.id', '=', 'flex_games.competition_id')
            ->orderBy('.flex_games.date', "desc")
            ->groupBy('flex_games.id')
            ->first();
        if (!$game) {
            return response()->json(["success" => false, "message" => "Game not found"]);
        }
        return response()->json(["success" => true, "game" => $game]);

    }

    public static function deleteGame(Request $request)
    {
        $rules = [
            "id" => "required|int",
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        try {
            GamesModel::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);
        }
        $games = GamesModel::selectRaw('flex_games.id,flex_games.location ,flex_games.date,flex_games.host_goals,flex_games.guest_goals, team.name as host_name, team.logo as host_logo, team2.name as guest_name,team2.logo as guest_logo, competition.name as competition_name')
            ->leftJoin('flex_teams as team', 'flex_games.host_id', '=', 'team.id')
            ->leftJoin('flex_teams as team2', 'flex_games.guest_id', '=', 'team2.id')
            ->leftJoin('flex_competitions as competition', 'competition.id', '=', 'flex_games.competition_id')
            ->orderBy('flex_games.date')
            ->groupBy('flex_games.id')
            ->get();
        return response()->json(["success" => true, "games" => $games]);

    }

    public static function getResults()
    {
        try {
            $contents = \File::get("https://www.sofascore.com/u-tournament/154/season/24028/standings/json?_=1583337690");
            return response()->json(["success" => true, "result" => $contents]);
        } catch (FileNotFoundException $e) {
            return response()->json(["success" => false, "result" => $e]);
        }

    }


}
