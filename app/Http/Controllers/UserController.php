<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\PostController;
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
        $all_post = Post::where('author', '=', $user->id)->get();
        foreach ($all_post as $post)
        {
            PostTag::where('post_id', '=', $post->id)->delete();
        }
        Post::where('author', '=', $user->id)->delete();
        User::where('id', '=', $user->id)->delete();

        return redirect()->route('home');
    }
}
