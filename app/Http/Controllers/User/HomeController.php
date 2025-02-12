<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CategoryMotorbike;
use App\Models\Motorbike;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $listMotorbikes = [];
        $categoryMotorbikes = CategoryMotorbike::where("status", "public")->get();
        $branchs = Branch::where("status", "public")->get();
        foreach ($categoryMotorbikes as $category) {
            $listMotorbikes[$category->id] = [
                'name' => $category->name,
                'slug' => $category->slug,
                'motorbikes' => [] // Khởi tạo danh sách xe rỗng
            ];
            $motorbikes = Motorbike::where("category_motorbike_id", $category->id)
                ->Where("status", "public")
                ->get();


            foreach ($motorbikes as $motorbike) {
                $listMotorbikes[$category->id]["motorbikes"][$motorbike->id] = $motorbike;
            }

        }

        $listPosts = Post::where("status", "public")->limit(8)->get();
        // dd($listMotorbikes);
        return view("frontend.home", compact("listMotorbikes", "listPosts", "branchs"));
    }
}
