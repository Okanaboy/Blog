<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Image;
use App\Models\Comment;
use App\Models\PostTag;
use App\Models\ImagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!isset($_GET['search']))
        {
            Carbon::setLocale('fr');
            $posts = Post::latest()->paginate(6);
            $nbr_posts = Post::count();
            $tags = Tag::all();
            return view('post.index')->with('posts', $posts)->with('nbr_posts', $nbr_posts)->with('tags', $tags);
        }
        else
        {
            Carbon::setLocale('fr');

            $search = $_GET['search'];

            $posts = Post::where('title', 'LIKE', "%{$search}%")
                        ->orWhere('content', 'LIKE', "%{$search}%")
                        ->latest()
                        ->paginate(6);
            $nbr_posts = $posts->count();

            $tags = Tag::all();
            return view('post.index')
                    ->with('posts', $posts)
                    ->with('nbr_posts', $nbr_posts)
                    ->with('tags', $tags);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        return view('post.create')->with('tags', $tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => Auth::user()->id
        ]);
        foreach($request->tag as $tag_id => $value)
        {
            PostTag::create([
                'post_id' => $post->id,
                'tag_id' => $value
            ]);
        }

        if (isset($request->images))
        {
            foreach ($request->images as $image)
            {
                $image_name = $image->store('posts');
                $post_image = Image::create([
                    'path' => $image_name
                ]);
                ImagePost::create([
                    'image_id' => $post_image->id,
                    'post_id' => $post->id
                ]);
            }
        }

        return redirect()->back()->with('message', 'New post added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        Carbon::setLocale('fr');
        $comments = Comment::where('post_id', $post->id)->orderBy('created_at', 'Desc')->get();
        $post->timestamps = false;
        $post->increment('views');

        $related = Post::whereHas('tags', function ($q) use ($post) {
            return $q->whereIn('name', $post->tags->pluck('name'));
        })
        ->where('id', '!=', $post->id)
        ->take(3)
        ->get();

        return view('post.show', compact('post', 'comments', 'related'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags_ids = PostTag::where('post_id', '=', $post->id)->get();

        foreach ($tags_ids as $key => $value)
        {
            $tags = Tag::where('id', '<>', $value->tag_id)->get();
        }
        return view('post.edit')->with('post', $post)->with('tags', $tags);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // dd($request);
        Post::where('id', '=', $post->id)
                ->update([
                    'title' => $request->title,
                    'content' => $request->content
                ]);

        if($request->images != null)
        {
            foreach ($request->images as $image)
            {
                $image_name = $image->store('posts');
                $post_image = Image::create([
                    'path' => $image_name
                ]);
                ImagePost::create([
                    'image_id' => $post_image->id,
                    'post_id' => $post->id
                ]);
            }
        }

        if (is_array($request->image_del) || is_object($request->image_del))
        {
            foreach ($request->image_del as $img_del => $value)
            {
                Image::where('id', '=', $value)->delete();
                ImagePost::where('image_id', "=", $value)->delete();
            }
        }

        if(is_array($request->tag) || is_object($request->tag))
        {
            foreach($request->tag as $tag_id => $value)
        {
            PostTag::create([
                'post_id' => $post->id,
                'tag_id' => $value
            ]);
        }
        }
        if (is_array($request->tag_del) || is_object($request->tag_del))
        {
            if (sizeof($request->tag_del) == (PostTag::where('post_id', '=', $post->id))->count())
            {
                return redirect()->back()->with('message', 'Keep at least one tag');
                die();
            }
            foreach ($request->tag_del as $tag_del => $value)
            {
                PostTag::where('tag_id', '=', $value)->delete();
            }
        }

        return redirect()->back()->with('message', 'Post edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // dd($post);
        $id_images = ImagePost::where('post_id', '=', $post->id)->get();
        foreach ($id_images as $key => $value)
        {
            Image::where('id', '=', $value->image_id)->delete();
        }
        Post::find($post->id)->delete();

        PostTag::where('post_id', '=', $post->id)->delete();
        ImagePost::where('post_id', '=', $post->id)->delete();
        return redirect()->back()->with('message', 'Post deleted successfully');
    }

    /**
     * Restore the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id)
    {
        Post::where('id', $id)->restore();
        return redirect()->back()->with('message', 'Post restored successfully');
    }

    /**
     * Remove definitly the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDefinitly(int $id)
    {
        Post::where('id', $id)->forceDelete();

        return redirect()->back()->with('message', 'Post deleted definitly');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function tag(Tag $tag)
    {
        return view('post.tag')->with('tag', $tag)->with('posts', $tag->posts()->latest()->paginate(3));
    }
}
