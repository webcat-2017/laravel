<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Psy\Util\Json;
use Spatie\Tags\HasTags;
use Illuminate\Support\Facades\DB;
use Image;
use Spatie\Tags\Tag;

class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.list', ['title' => 'Список новин']);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'title'       => 'required',
           'description' => 'required',
           'image' => 'image|mimes:jpeg,jpg,png|max:10240',
        ]);

        $attributes = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $request->status ? $attributes['status'] = 1 : $attributes['status'] = 0;

        if($image = $request->file('image'))
        {
            $imagePath = 'image/';
            $thumbnailsPath = 'image/thumbnails/';
            $previewPath = 'image/previews/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();

            $img = Image::make($image->path());
            $img->resize(35, 35, function ($const) {
                $const->aspectRatio();
            })->save($thumbnailsPath.$profileImage);

            $img = Image::make($image->path());
            $img->resize(350, 200, function ($const) {
                $const->aspectRatio();
            })->save($previewPath.$profileImage);

            $image->move($imagePath, $profileImage);

            $attributes['image'] = $profileImage;
        }

        if ($request->tags)
        {
            $tags_arr = explode(',', $request->tags);

            if($request->id)
            {
                $new = News::find($request->id);

                $tags = $new->tags()->get();

                $old = [];

                foreach ($tags as $tag)
                {
                    $old = [$tag->name];
                }

                $remove = array_diff($old, $tags_arr);

                if($remove){
                    foreach ($remove as $tag)
                    {
                        $target = '%'. $tag .'%';

                        $founds = News::where('description', 'like', $target)->get();

                        foreach ($founds as $found)
                        {
                            $description = preg_replace('/<a href="(.*?)">'.$tag.'<\/a>/',$tag, $found->description);

                            $found->description = $description;

                            $found->save();
                        }

                        $new->detachTag($tag);

                        Tag::findFromString($tag)->delete();

                    }

                }

            }

            $attributes = array_merge($attributes, ['tags' => $tags_arr]);
        }

        $news = News::updateOrCreate(['id' => $request->id], $attributes);

        $created_at = $news->created_at->format('Y-m-d H:i:s');


        /*
         * link
         *
         */

        $tags = $news->tags()->get();

        if(!empty($tags[0]))
        {
            foreach ($tags as $tag)
            {
                $target = '%'. $tag->name .'%';

                $founds = News::where('description', 'like', $target)->get();

                foreach ($founds as $found)
                {
                    if ($news->id != $found->id)
                    {
                        $description = preg_replace('/'.$tag->name.'/','<a href="'.preg_replace('/id\?/','',route('new',['id', $news->id])).'">$0</a>', $found->description);

                        $found->description = $description;

                        $found->save();
                    }
                }
            }
        }

        $all_tags = Tag::all();

        foreach ($all_tags as $tag)
        {
            if($result = preg_match('/'. $tag->name .'/',$news->description))
            {

                $taggables_id  = DB::table('taggables')->where('tag_id', $tag->id)->get();

                if(!empty($taggables_id[0]))
                {
                    $id_new = $taggables_id[0]->taggable_id;

                    if($news->id != $id_new)
                    {
                        $description = preg_replace('/'.$tag->name.'/','<a href="'.preg_replace('/id\?/','',route('new',['id', $id_new])).'">$0</a>', $news->description);

                        $news->description = $description;

                        $news->save();
                    }
                }

            }
        }

        return response()->json(['code'=>200, 'message'=>'Запись успешно создана','data' => $news, 'created_at' => $created_at], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::find($id);

        $attributes = [
            'news' => $news,
        ];

        $tags = $news->tags()->get();

        if(!empty($tags[0]))
        {
            $str_tags = "";

            foreach ($tags as $tag)
            {

                $str_tags  .= (string)$tag->name . ",";
            }

            $str_tags = substr($str_tags, 0, -1);

            $attributes['tags'] = $str_tags;
        }


        return response()->json($attributes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        News::find($id)->delete();


        return response()->json(['success'=>'Запись успешно удалена']);
    }
}
