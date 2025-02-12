<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //

    public function showPage($slugPage){
        $page = Page::where("slug", $slugPage)->first();
        if(!$page){
            abort(404);
        }
        return view("frontend.page", compact("page"));
    }
}
