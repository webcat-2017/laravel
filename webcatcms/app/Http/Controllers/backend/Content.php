<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Resource_;
use Psy\Util\Json;

class Content extends Controller
{
    public function save_new(Request $request)
    {
       return response()->json($request);
    }

    public function list_pages()
    {

    }

    public function list_posts()
    {

    }

    public function List_news()
    {

        return view('backend.list_content', ['title' => __('backend.title_news')]);
    }

    public function list_tags()
    {

    }
}
