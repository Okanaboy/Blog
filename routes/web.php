<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/details-user/{user}', [PageController::class, 'showUser'])->name('show.user');

Route::get('/posts', [PostController::class, 'index'])->name('post.index');
Route::get('/tags/{tag}', [PostController::class, 'tag'])->name('post.tag');

Route::get('/tags', [TagController::class, 'index'])->name('tag.index');


//User
Route::middleware(['auth', 'verified'])->group(function (){

    Route::resource('post', PostController::class)->except('index');
    Route::resource('tag', TagController::class)->except('index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/comment/{post}', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::put('/update', [UserController::class, 'update'])->name('user.update');
});

//Admin
Route::middleware(['role:admin', 'auth'])->prefix('admin')->group(function (){

    Route::get('/posts', [DashboardController::class, 'posts'])->name('admin.posts');
    Route::get('/posts/trashed', [DashboardController::class, 'postsTrashed'])->name('admin.posts.trashed');
    Route::delete('/posts/{id}/forcedestroy', [PostController::class, 'destroyDefinitly'])->name('admin.posts.forcedelete');
    Route::get('/posts/{id}/restore', [PostController::class, 'restore'])->name('admin.posts.restore');
    Route::get('/posts/most-viewed', [DashboardController::class, 'postsMostViewed'])->name('admin.posts.mostviewed');
    Route::get('/search', [DashboardController::class, 'search'])->name('admin.search');
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/tags', [DashboardController::class, 'tags'])->name('admin.tags');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('admin.profile');
    Route::put('/update', [UserController::class, 'update'])->name('admin.update');
    Route::get('/user/{user}/edit', [DashboardController::class, 'editUser'])->name('admin.user.edit');
    Route::get('/users/trashed', [DashboardController::class, 'usersTrashed'])->name('admin.users.trashed');
    Route::delete('/user/{user}/delete', [DashboardController::class, 'destroyUser'])->name('admin.user.destroy');
    Route::delete('/user/{id}/forcedestroy', [UserController::class, 'forceDestroy'])->name('admin.users.forcedestroy');
    Route::get('/posts/{id}/restore', [UserController::class, 'restore'])->name('admin.user.restore');
    Route::put('/user/{user}/update', [DashboardController::class, 'updateUser'])->name('admin.user.update');
    Route::get('/post/{post}/edit', [DashboardController::class, 'editPost'])->name('admin.post.edit');
    Route::put('/post/{post}/update', [DashboardController::class, 'updatePost'])->name('admin.post.update');
});


require __DIR__.'/auth.php';
