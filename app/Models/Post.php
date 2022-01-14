<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $guarded = ['id'];

    public const image_sizes = [300,200];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setThumbnailUri($request)
    {
        if (!$this->exists) {
            throw new \Exception('Must be saved first to set Image Uri.');
        }
        $storageDir = $this->storageDir();

        $timestamp = time();

        $thumbnail_path = "$storageDir/images/$timestamp";

        Storage::disk('public')->put($thumbnail_path, $request->file('thumbnail'));

        $this->thumbnail = $thumbnail_path;
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
