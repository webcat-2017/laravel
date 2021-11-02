<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;
use Illuminate\Support\Facades\DB;


class ValidateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {

        if($tag = Tag::FindFromString($request->tag))
        {
            if($request->id)
            {
                $result = DB::table('taggables')->where(['taggable_id' => $request->id, 'tag_id' => $tag->id])->get();

                if(empty($result[0])) return response()->json(['tag' => $tag->name]);

            }else
                {
                    return response()->json(['tag' => $tag->name]);
                }
        }
    }
}
