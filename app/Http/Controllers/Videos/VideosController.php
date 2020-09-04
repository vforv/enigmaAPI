<?php

namespace App\Http\Controllers\Videos;

use App\Http\Controllers\Controller;
use App\Models\Videos\VideosModel;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    public static function addVideo(Request $request)
    {
        $rules = [
            "title" => "required|string",
            "date" => "required|string",
            "link" => "required|string"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $video_id = "";
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $request->get("link"), $match)) {
            $video_id = $match[1];
        }

        $date = date("Y-m-d", strtotime($request->get("date")));

        $video = VideosModel::create(["title" => $request->get("title"), "link" => $video_id, "date" => $date]);

        if (!$video) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "videos" => self::handleGetVideos()]);
    }

    public static function getAllVideos()
    {
        return response()->json(["success" => true, "videos" => self::handleGetVideos()]);
    }

    public static function deleteVideo(Request $request)
    {
        $rules = [
            "id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $video = VideosModel::find($request->get("id"));

        if (!$video) {
            return response()->json(["success" => false, "message" => "Video could not be found"]);
        }

        try {
            $video->delete();
            return response()->json(["success" => true, "videos" => self::handleGetVideos()]);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e]);

        }
    }

    public static function editVideo(Request $request)
    {
        $rules = [
            "id" => "required|int",
            "title" => "required|string",
            "date" => "required|string",
            "link" => "required|string"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $video_id = "";
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $request->get("link"), $match)) {
            $video_id = $match[1];
        }

        $date = date("Y-m-d", strtotime($request->get("date")));

        $video = VideosModel::find($request->get("id"))->update(["title" => $request->get("title"), "link" => $video_id, "date" => $date]);

        if (!$video) {
            return response()->json(["success" => false, "message" => "Something went wrong"]);
        }
        return response()->json(["success" => true, "videos" => self::handleGetVideos()]);
    }

    private static function handleGetVideos()
    {
        return VideosModel::orderBy("date", "desc")->orderBy("id", "desc")->get();
    }
}
