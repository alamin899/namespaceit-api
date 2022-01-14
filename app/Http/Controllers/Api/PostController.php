<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PostResource::collection(Post::paginate(getPagination()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = Auth::user()->posts()->create([
            'title' => $request->title,
            'slug' => str_replace(" ", "-", $request->slug),
            'body' => $request->body
        ]);

        if ($request->file) {
            $post->setThumbnail($request->file('thumbnail'));

            $post->save();
        }

        if ($post) {
            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => "post created successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => "something went wrong",
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::where('slug',$slug)->first();

        if ($post) {
            return response()->json([
                'success' => true,
                'data' => new PostResource($post),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => "something went wrong",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        $update = $post->update([
            'title' => $request->title,
            'slug' => str_replace(" ", "-", $request->slug),
            'body' => $request->body
        ]);

        if ($request->file) {
            $post->setThumbnail($request->file('thumbnail'));

            $post->save();
        }

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => "post updated successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "something went wrong",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Post::find($id)->delete();

        if ($destroy) {
            return response()->json([
                'success' => true,
                'message' => "post deleted successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "something went wrong",
            ]);
        }
    }
}
