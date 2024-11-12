<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Post;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class PostController extends Controller
{

    private $path = "images/";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Post::with("kategori")->get();

        return response()->view("post.index",[
            "data" => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::all();
        return response()->view("post.create",[
            "kategori" => $kategori,
        ]);
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
            "kategori_id" => "required",
            "title" => "required",
            "content" => "required",
            "image" => "required|mimes:jpeg,jpg,png,gif|max:2048",

        ]);

        $image = $request->file("image");
        $name = $image->hashName();
        $image->move($this->path, $name);
        Post::create([
            "uuid" => Uuid::uuid4()->toString(),
            "kategori_id" => $request->kategori_id,
            "title" => $request->title,
            "content" => $request->content,
            "slug" =>Str::slug($request->title),
            "image" => $name,
        ]);
        toastr()->success("Data Berhasil Di Tambahkan");
        return redirect()->route("admin.post.index");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::with("kategori")->where("uuid", $id)->first();
        $kategori = Kategori::all();
        return view("post.edit", compact("post", "kategori"));
        
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
        $post = Post::findOrFail($id);
        $request->validate([
            "kategori_id" => "required",
            "title" => "required",
            "content" => "required",
            "image" => "nullable|mimes:jpeg,jpg,png,gif|max:2048",
        ]);
        if ($request->hasFile("image")) {
            if (file_exists($this->path . $post->image)) {
                unlink($this->path . $post->image);
            }
            $image = $request->file("image");
            $name = $image->hashName();
            $image->move($this->path, $name);
            $post->update([
                "kategori_id" => $request->kategori_id,
                "title" => $request->title,
                "content" => $request->content,
                "slug" => Str::slug($request->title),
                "image" => $name,
            ]);
        } else {
            $post->update([
                "kategori_id" => $request->kategori_id,
                "title" => $request->title,
                "content" => $request->content,
                "slug" => Str::slug($request->title),
            ]);
        }
        toastr()->success("Data Berhasil Di Ubah");
        return redirect()->route("admin.post.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if (file_exists($this->path . $post->image)) {
            unlink($this->path . $post->image);
        }
        $post->delete();
        toastr()->success("Data Berhasil Di Hapus");
        return redirect()->route("admin.post.index");
    }
}
