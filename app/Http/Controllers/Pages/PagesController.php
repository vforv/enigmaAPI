<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Images\ImagesModel;
use App\Models\Pages\PagesContentModel;
use App\Models\Pages\PagesModel;
use App\Traits\HandleImageOptimization;
use App\Traits\HandleImageUpload;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    use HandleImageUpload, HandleImageOptimization;

    public static function addPage(Request $request)
    {
        $pages = json_decode($request->get('pages'));
        $data = [];
        $page = PagesModel::create([]);
        $rules = [
            'language_id' => 'required|int|min:0',
            'title' => 'required|string',
            'slug' => 'required|string|unique:flex_pages_content',
        ];
        $time = Carbon::now();
        foreach ($pages as $p) {
            $validation = ValidateHttpRequest($rules, $p, true);
            $p->page_id = $page->id;
            $p->created_at = $time;
            $p->updated_at = $time;
            if ($validation) {
                try {
                    PagesModel::find($page->id)->delete();
                } catch (\Exception $e) {
                }
                return response()->json(array_merge($validation));
            }
            array_push($data, (array)$p);
        }

        $names = [];
        $images = $request->file('images');

        $uploaded = self::imageUploadHandler($images);

        $optimization = self::handleImageOptimization(1920, true, $uploaded['images'], public_path($uploaded['path']));

        $counter = 0;
        foreach ($uploaded["images"] as $image) {
            $img = new ImagesModel();
            $img->image = $uploaded['path'] . $image;
            $img->page_id = $page->id;
            $img->order_number = $counter;
            $img->save();
            $counter++;
        }
        $pageContent = PagesContentModel::insert($data);
        if ($pageContent) {
            return response()->json(["success" => true, "message" => "Pages successfully created.", "status" => 200, 'images' => $uploaded["images"], "optimization" => $optimization]);
        } else {
            try {
                PagesModel::find($page->id)->delete();
            } catch (\Exception $e) {
            }
            return response()->json(["success" => false, "message" => "Something went wrong", "status" => 400]);
        }
    }

    public static function deletePage(Request $request)
    {
        $rules = [
            'page_id' => 'required|int|min:0',
        ];

        $validation = ValidateHttpRequest($rules, $request);

        if ($validation) {
            return response()->json($validation);
        }

        try {
            $page = PagesModel::find($request->get("page_id"));
            if ($page) {
                $images = ImagesModel::where('page_id', '=', $request->get('page_id'))->get();
                $page->delete();
                $pages = PagesModel::all();
                $fileExtensions = [".jpg", ".webp", "-min.jpg", "-min.webp", "-mobile.jpg", "-mobile.webp", "-thumbnail.jpg", "-thumbnail.webp"];
                $filesDeleted = [];
                foreach ($images as $image) {
                    $path_parts = pathinfo($image->image);
                    $filename = $path_parts['filename'];
                    $dirname = $path_parts['dirname'] . "/";
                    foreach ($fileExtensions as $ext) {
                        Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
                        array_push($filesDeleted, $dirname . $filename . $ext);
                    }
                }
                return ["success" => true, "message" => "Page deleted.", "status" => 200, "del" => $filesDeleted];
            } else {
                $pages = PagesModel::all();
                return ["success" => false, "message" => "Page not found.", "status" => 404];
            }
        } catch (\Exception $e) {
            $pages = PagesModel::all();
            return ["success" => false, "message" => $e, "status" => 400];
        }

    }

    public static function deleteImage(Request $request)
    {
        $rules = [
            "image" => "required|string",
            "image_id" => "required|int"
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json(array_merge($validation));
        }

        $fileExtensions = [".jpg", ".webp", "-min.jpg", "-min.webp", "-mobile.jpg", "-mobile.webp", "-thumbnail.jpg", "-thumbnail.webp"];
        $path_parts = pathinfo($request->get("image"));
        $filename = $path_parts['filename'];
        $dirname = $path_parts['dirname'] . "/";
        $filesDeleted = [];
        try {
            ImagesModel::where('id', '=', $request->get('image_id'))->delete();
            foreach ($fileExtensions as $ext) {
                Storage::disk('uploadImage')->delete($dirname . $filename . $ext);
                array_push($filesDeleted, $dirname . $filename . $ext);
            }
            return response()->json(['success' => true, 'message' => "Images deleted"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e]);
        }


    }

    public static function getPage(Request $request)
    {
        if (!$request->has('admin')) {
            $page = PagesContentModel::where("slug", "=", $request->get('slug'))->first();
            $images = ImagesModel::where("page_id", "=", $page['page_id'])->orderBy('order_number')->get();
            $latestNews = PagesModel::where('flex_pages.id', '<>', $page['page_id'])
                ->where("content.category_id", "=", 2)->select(['id', 'flex_pages.updated_at', 'content.title', 'content.slug', 'content.date', 'content.description', 'images.image as cover'])
                ->where("language_id", "=", $page->language_id)
                ->leftJoinSub('select page_id ,language_id, title, description, content, date, slug, category_id from flex_pages_content', 'content', 'content.page_id', '=', 'flex_pages.id')
                ->leftJoinSub('select image, page_id from flex_pages_images where order_number=0', "images", "images.page_id", "=", "flex_pages.id")
                ->orderBy("created_at", "desc")
                ->groupBy('id')
                ->limit(3)
                ->get();

            foreach ($latestNews as $ln) {
                $ln['category'] = "Vijesti";
            }
            $page->images = $images;
            $page->category = "Vijesti";
            if ($page) {
                return ["success" => true, "page" => $page, "latestNews" => $latestNews];
            } else {
                return ["success" => false, "message" => "Page not found.", "status" => 404];
            }
        }
        $page = PagesContentModel::where("slug", "=", $request->get('slug'))
            ->orderBy("language_id")->first();
        $images = ImagesModel::where("page_id", "=", $page['page_id'])->orderBy('order_number')->get();
        $language_id = $request->has('language_id') ? " where language_id = " . $request->get('language_id') : "";
        if ($page) {
            return ["success" => true, "page" => $page, "images" => $images];
        } else {
            return ["success" => false, "message" => "Page not found.", "status" => 404];
        }
    }

    public static function updatePage(Request $request)
    {
        $pages = json_decode($request->get('pages'));
        $data = [];
        $rules = [
            'language_id' => 'required|int|min:0',
            'page_id' => 'required|int|min:0',
            'title' => 'required|string',
            'slug' => 'required|string',
        ];
        $time = Carbon::now();
        $page_id = 0;
        $counter = 0;
        foreach ($pages as $p) {
            $validation = ValidateHttpRequest($rules, $p, true);
            $p->updated_at = $time;
            $page_id = $p->page_id;
            if ($validation) {
                return response()->json(array_merge($validation));
            }
            try {
                PagesContentModel::where([['page_id', '=', $p->page_id], ['language_id', '=', $p->language_id], ["id", "=", $p->id]])->update((array)$p);
            } catch (\Exception $e) {
                return response()->json(["success" => false, "message" => $e, "status" => 400, "p" => $p]);
            }
        }
        try {
            foreach ($pages as $page) {
                PagesModel::find($page->page_id)->update(['updated_at' => $time]);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e, "status" => 400, 'id' => $pages[0]->page_id]);
        }
        return response()->json(["success" => true, "message" => "Pages successfully updated.", "status" => 200]);
    }


    public static function getAllPages(Request $request)
    {
        $rules = [
            'language_id' => 'required|int',
            "limit" => 'required|int',
            "category_id" => 'required|int'
        ];
        $validation = ValidateHttpRequest($rules, $request);
        if ($validation) {
            return response()->json($validation);
        }
        $offset = $request->has("offset") ? $request->get("offset") : 0;
        $pages = PagesModel::where('content.category_id', "=", $request->get("category_id"))
            ->select(['id', 'flex_pages.updated_at', 'content.title', 'content.slug', 'content.date', 'content.category_id', 'content.description', 'images.image as cover'])
            ->leftJoinSub('select page_id ,language_id, title, description, content, date, slug, category_id from flex_pages_content where language_id = ' . $request->get('language_id'), 'content', 'content.page_id', '=', 'flex_pages.id')
            ->leftJoinSub('select image, page_id from flex_pages_images where order_number=0', "images", "images.page_id", "=", "flex_pages.id")
            ->orderBy("created_at", "desc")
            ->groupBy('id')
            ->offset($offset)
            ->limit($request->get("limit"))
            ->get();

        if ($pages) {
            foreach ($pages as $page) {
                $page['category'] = "Vijesti";
                $page['date'] = date('d.m.Y', strtotime($page['date']));
            }
            return ["success" => true, "pages" => $pages];
        } else {
            return ["success" => false, "message" => "No pages found", "status" => 404];
        }
    }


    public static function addImages(Request $request)
    {
        $images = $request->file('images');
        $page_id = $request->get('page_id');

        $uploaded = self::imageUploadHandler($images);
        $max = ImagesModel::where('page_id', '=', $page_id)->max('order_number');

        $optimization = self::handleImageOptimization(1920, true, $uploaded['images'], public_path($uploaded['path']));

        $counter = 0;
        foreach ($uploaded["images"] as $image) {
            $img = new ImagesModel();
            $img->image = $uploaded['path'] . $image;
            $img->page_id = $page_id;
            $img->order_number = $counter;
            $img->save();
            $counter++;
        }

        return response()->json(["success" => true, "message" => "Images successfully uploaded.", "status" => 200, "optimization" => $optimization]);
    }

    public static function sortImages(Request $request)
    {
        $images = $request->get('images');
        $counter = 0;
        foreach ($images as $image) {
            $img = ImagesModel::where('id', '=', $image['id'])->update(['order_number' => $counter]);
            $counter++;
        }
    }
}
