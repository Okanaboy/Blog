<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use App\Models\PostTag;
use App\Models\ImagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;

class DashboardController extends Controller
{

    public function index()
    {
        if(Auth::user()->hasRole('admin'))
        {
            $posts = Post::count();
            $users = User::count();
            $tags = Tag::count();
            return view('admin.index')
                    ->with('users', $users)
                    ->with('posts', $posts)
                    ->with('tags', $tags);
        }
        elseif (Auth::user()->hasRole('user'))
        {
            return view('user.index');
        }
    }

    public function posts()
    {
        $posts = Post::latest()->paginate(12);
        return view('admin.posts.index')->with('posts', $posts);
    }

    public function postsTrashed()
    {
        $posts = Post::onlyTrashed()->latest()->paginate(12);
        return view('admin.posts.trashed')->with('posts', $posts);
    }

    public function postsMostViewed()
    {
        $posts = Post::orderBy('views', 'Desc')->latest()->paginate(12);
        return view('admin.posts.mostviewed')->with('posts', $posts);
    }

    public function users()
    {
        $users = User::latest()->paginate(12);
        return view('admin.users.index')->with('users', $users);
    }

    public function tags()
    {
        $tags = Tag::latest()->paginate(12);
        return view('admin.tags.index')->with('tags', $tags);
    }

    public function search(Request $request)
    {
        //
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit')->with('user', $user);
    }

    public function updateUser(Request $request, User $user)
    {
        User::where('id', '=', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->image != NULL)
        {
            if (Auth::user()->avatar_path == NULL)
            {
                $image_name = $request->image->store('users');
                User::where('id', '=', $user->id)->update([
                    'avatar_path' => $image_name
                ]);
            }
            else
            {
                if(Storage::exists($user->avatar_path))
                {
                    Storage::delete($user->avatar_path);

                    $image_name = $request->image->store('users');
                    User::where('id', '=', $user->id)->update([
                        'avatar_path' => $image_name
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Profile updated');
    }

    public function editPost(Post $post)
    {
        $tags_ids = PostTag::where('post_id', '=', $post->id)->get();

        foreach ($tags_ids as $key => $value)
        {
            $tags = Tag::where('id', '<>', $value->tag_id)->get();
        }
        return view('admin.posts.edit')->with('post', $post)->with('tags', $tags);
    }

    public function updatePost(UpdatePostRequest $request, Post $post)
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
}
