<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;


class News extends Model
{
    use HasTags;
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'tags',
    ];


    protected $attributes = [
        'image' =>'no_photo.jpg',
    ];

    public static function boot() {

        parent::boot();

        static::deleting(function($item) {

            $tags = $item->tags()->get();

            $item->detachTags($tags);

            foreach ($tags as $tag)
            {

                $target = '%'. $tag->name .'%';

                $founds = News::where('description', 'like', $target)->get();

                foreach ($founds as $found)
                {
                    $description = preg_replace('/<a href="(.*?)">'.$tag->name.'<\/a>/',$tag->name, $found->description);

                    $found->description = $description;

                    $found->save();
                }

               Tag::findFromString($tag->name)->delete();
            }
            if((string)$item->image != 'no_photo.jpg')
            {
               $path = public_path('image/');

                $files = News::rmImage($path . $item->image);

                foreach ($files as $file)
                {
                    if(file_exists($file)) unlink($file);
                }
            }
        });
    }

    public static function rmImage($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, News::rmImage($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }

    public function getExcerptAttribute()
    {
        return str_limit(strip_tags($this->description),200);
    }

}
