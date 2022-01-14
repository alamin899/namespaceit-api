<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    public const image_sizes = [300,200];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setThumbnailUri(?UploadedFile $file)
    {
        if (!$file) {
            $this->thumbnail = null;
            return;
        }

        if (!$this->exists) {
            return response()->json(["message" => "The model must exists to get storageDir"]);
        }

        $storageDir = $this->storageDir();

        $timestamp = time();

        $thumbnail_path = "$storageDir/images/$timestamp";

        $originalWebP = $this->generateImages($file, $thumbnail_path, self::image_sizes);

        $this->thumbnail = $originalWebP;
    }

    public function storageDir(): string
    {
        if (!$this->exists) {
            return response()->json(["message" => "The model must exists to get storageDir"]);
        }

        $id = $this->id;

        return "posts/$id";
    }
}
