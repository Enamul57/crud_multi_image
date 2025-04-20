<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $index = Post::select('id', 'title')->paginate(5);
        return response()->json($index);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:6',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
        ], [
            'images.*.max' => "Image must not be more than 1MB",
            'images.*.mimes' => "Image should be jpeg, jpg, png format",
        ]);
        if ($validator->fails()) {
            return response()->json(["Error" => $validator->errors()], 422);
        }
        try {
            $post = Post::create(['title' => $request->title]);
            $images  = $request->file('images');

            if (isset($images)) {
                foreach ($images as $image) {
                    $name = $image->hashName();
                    $path = 'images/' . $name;
                    $image->storeAs('images', $name, 'public');
                    $post->images()->save(new Image([
                        'file_path' => $path,
                    ]));
                }
            }
            return response()->json($images);
        } catch (\Exception $e) {
            return response()->json(['Upload Failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post = Post::with('images')->where('id', $id)->first();
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePost(Request $request)
    {
        //
        $id  = $request->id;
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(["Error" => $validator->errors()], 422);
        }


        if ($request->has('images') && is_array($request->images)) {
           try{
             //update post title
             Post::where('id', $id)->update(['title' => $request->title]);
             //fetch the image data
             $images = Image::where('post_id', $id)->get();
 
             //instance of the post 
             $post = Post::find($id);
 
             //deleting images from storage
             foreach ($images as $image) {
                 if (Storage::disk('public')->exists($image->file_path)) {
                     Storage::disk('public')->delete($image->file_path);
                 }
             }
             //delete previous image from model
             Image::where('post_id', $id)->delete();
 
             //new images from requests
             $images  = $request->file('images');
 
             if (isset($images)) {
                 foreach ($images as $image) {
                     $name = $image->hashName();
                     $path = 'images/' . $name;
                     $image->storeAs('images', $name, 'public');
                     
                     $post->images()->save(new Image([
                         'file_path' => $path,
                     ]));
                 }
             }
           }catch(\Exception $e){
            return response()->json(['Update Failed: ' . $e->getMessage()]);
           }
        } else {
            Post::where('id', $id)->update(['title' => $request->title]);
        }
    }

    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
       
        $post = Post::find($id);
        foreach($post->images as $image){
            if(Storage::disk('public')->exists($image->file_path)){
                Storage::disk('public')->delete($image->file_path);
            }
        }
        $post->images()->delete();
        $post->delete();
    }
}
