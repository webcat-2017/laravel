<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
class MainController extends Controller
{

    public function index()
    {
        $context = [];

        $context['title'] = 'Головні новини';

        $news = News::where('status', '=', true)->orderBy('created_at', 'DESC')->paginate(8);

        $context['news'] = $news;

        return view('frontend.index', $context);
    }

    public function show($id)
    {

        $context = [];

        if($new = News::find($id))
        {
            $context = [];

            $context['title'] = $new->title;

            $context['new'] = $new;

            if($tags = $new->tags()->get()) $context['tags'] = $tags;

            return view('frontend.show', $context);
        }
        else
        {
            return redirect()->route('home');
        }


    }


}
