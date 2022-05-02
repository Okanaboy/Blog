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
Route::get('/posts', [PostController::class, 'index'])->name('post.index');
Route::get('/tags/{tag}', [PostController::class, 'tag'])->name('post.tag');
Route::get('/tags', [TagController::class, 'index'])->name('tag.index');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::middleware(['auth'])->group(function (){

    Route::resource('post', PostController::class)->except('index');
    Route::resource('tag', TagController::class)->except('index');
    Route::post('/comment', [CommentController::class, 'store'])->name('comment');
    Route::get('/admin/posts', [DashboardController::class, 'posts'])->name('admin.posts');
    Route::get('/admin/search', [DashboardController::class, 'search'])->name('admin.search');
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/tags', [DashboardController::class, 'tags'])->name('admin.tags');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/profile', [DashboardController::class, 'profile'])->middleware(['auth'])->name('user.profil');
Route::get('/admin/user/edit/{user}', [DashboardController::class, 'editUser'])->middleware(['auth'])->name('admin.user.edit');
Route::put('/admin/user/update/{user}', [DashboardController::class, 'updateUser'])->middleware(['auth'])->name('admin.user.update');
Route::put('/profile-update', [UserController::class, 'update'])->middleware(['auth'])->name('user.update');
Route::delete('/user-destroy/{user}', [UserController::class, 'destroy'])->middleware(['auth'])->name('user.destroy');

require __DIR__.'/auth.php';
