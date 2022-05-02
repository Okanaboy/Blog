<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{

    public function index()
    {
        if(Auth::user()->hasRole('admin'))
        {
            $posts = Post::count();
            $users = User::count();
            $tags = Tag::count();
            return view('admin.index')->with('users', $users)->with('posts', $posts)->with('tags', $tags);
        }
        elseif (Auth::user()->hasRole('user'))
        {
            return view('user.index');
        }
    }

    public function posts()
    {
        if(Auth::user()->hasRole('admin'))
        {
            $users = User::latest()->paginate(12);
            return view('admin.posts.index')->with('users', $users);
        }
    }

    public function users()
    {
        if(Auth::user()->hasRole('admin'))
        {
            $users = User::latest()->paginate(12);
            return view('admin.users.index')->with('users', $users);
        }
    }

    public function tags()
    {
        if(Auth::user()->hasRole('admin'))
        {
            $tags = Tag::latest()->paginate(12);
            return view('admin.tags.index')->with('tags', $tags);
        }
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
}
