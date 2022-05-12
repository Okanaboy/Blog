<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\PostController;
use App\Models\Comment;
use App\Models\ImagePost;
use App\Models\Post;
use App\Models\PostTag;

class UserController extends Controller
{


    public function update(UpdateUserRequest $request)
    {
        User::where('id', '=', Auth::user()->id)->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->image != NULL)
        {
            if (Auth::user()->avatar_path == NULL)
            {
                $image_name = $request->image->store('users');
                User::where('id', '=', Auth::user()->id)->update([
                    'avatar_path' => $image_name
                ]);
            }
            else
            {
                if(Storage::exists(Auth::user()->avatar_path))
                {
                    Storage::delete(Auth::user()->avatar_path);

                    $image_name = $request->image->store('users');
                    User::where('id', '=', Auth::user()->id)->update([
                        'avatar_path' => $image_name
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Profile updated');
    }


    public function destroy(User $user)
    {
        $all_user = Post::where('author', '=', $user->id)->get();
        foreach ($all_user as $post)
        {
            PostTag::where('post_id', '=', $post->id)->delete();
            ImagePost::where('post_id', '=', $post->id)->delete();
        }
        Comment::where('author_id', '=', $user->id)->delete();
        Post::where('author', '=', $user->id)->delete();
        User::where('id', '=', $user->id)->delete();

        return redirect()->route('home');
    }

    public function forceDestroy(int $id)
    {
        User::where('id', $id)->forceDelete();

        return redirect()->back()->with('message', 'User deleted definitly');
    }

    public function restore(int $id)
    {
        User::where('id', $id)->restore();

        $all_post = Post::where('author', '=', $id)->get();
        foreach ($all_post as $post)
        {
            PostTag::where('post_id', '=', $post->id)->restore();
            ImagePost::where('post_id', '=', $post->id)->restore();
        }

        $all_comment = Comment::where('author_id', '=', $id)->get();
        foreach ($all_comment as $comment)
        {
            Comment::where('id', '=', $comment->id)->restore();
        }
        Post::where('author', '=', $id)->restore();

        return redirect()->back()->with('message', 'User restored successfully');
    }
}
