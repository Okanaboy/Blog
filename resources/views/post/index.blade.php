<?php use App\Models\User; ?>
<x-app-layout>
    <x-slot name="header">
        <form action="{{ route('post.index') }}" method="get">
            <div class="pt-2 relative mx-96 text-gray-600">
                <input class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                type="search" name="search" placeholder="Search">
                <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                    viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                    width="512px" height="512px">
                    <path
                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                </svg>
                </button>
            </div>
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-x-hidden bg-gray-100">
                <div class="px-6 py-8">
                    <div class="container flex justify-between mx-auto">
                        <div class="w-full lg:w-8/12">
                            <div class="flex items-center justify-between">
                                <h1 class="text-xl font-bold text-gray-700 md:text-2xl">Post</h1>
                                <div>
                                    <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option>Latest</option>
                                        <option>most viewed</option>
                                    </select>
                                </div>
                            </div>
                            @forelse ($posts as $post)
                            <div class="mt-6">
                                <div class="max-w-4xl px-10 py-6 mx-auto bg-white rounded-lg shadow-md">
                                    <div class="flex items-center justify-between">
                                        <span class="font-light text-gray-600">
                                            publié {{ $post->created_at->diffForHumans() }}
                                            <span class="ml-3 font-semibold text-green-600">{{ $post->views }} vue</span>
                                            @auth
                                            @if (Auth::user()->email == (User::find($post->author))->email)

                                            <x-dropdown>
                                                <x-slot name="trigger">
                                                    <button title="Action" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('post.edit', $post)">
                                                        {{ __('Edit') }}
                                                    </x-dropdown-link>
                                                    <form action="{{ route('post.destroy', $post) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="w-full block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-red-500 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                            {{ __('Delete') }}
                                                    </button>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                            @endif
                                            @endauth
                                        </span>

                                        <div class="flex justify-end">
                                            @foreach ($post->tags as $tag)
                                            <a href="{{ route('post.tag', $tag) }}" class="mx-1 px-2 py-1 font-bold text-gray-100 bg-gray-600 rounded hover:bg-gray-500">
                                                {{ $tag->name }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('post.show', $post) }}" class="text-2xl font-bold text-gray-700 hover:underline">
                                            {{ $post->title }}
                                        </a>
                                        <p class="mt-2 text-gray-600">
                                            {{ Str::limit($post->content, 200) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between mt-4">
                                        <a href="{{ route('post.show', $post) }}" class="text-blue-500 hover:underline">Read more</a>
                                        <div>
                                            <a href="#" class="flex items-center">
                                                <img src="{{ ((User::find($post->author))->avatar_path != NULL) ? asset('storage/' . (User::find($post->author))->avatar_path) : Avatar::create((User::find($post->author))->name)->toBase64() }}" alt="avatar" class="hidden object-cover w-10 h-10 mx-4 rounded-full sm:block">
                                                <h1 class="font-bold text-gray-700 hover:underline">
                                                    {{ (User::find($post->author))->name }}
                                                </h1>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="flex justify-center my-10 text-4xl font-bold">
                                Aucun post
                            </div>
                            @endforelse
                            <div class="mt-8">
                                <div class="flex">
                                    {{ $posts->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="hidden w-4/12 -mx-8 lg:block">
                            <div class="px-8">
                                <h1 class="mb-4 text-xl font-bold text-gray-700">Authors</h1>
                                <div class="flex flex-col max-w-sm px-6 py-4 mx-auto bg-white rounded-lg shadow-md">
                                    <ul class="-mx-4">
                                        <li class="flex items-center"><img
                                                src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=731&amp;q=80"
                                                alt="avatar" class="object-cover w-10 h-10 mx-4 rounded-full">
                                            <p><a href="#" class="mx-1 font-bold text-gray-700 hover:underline">Alex John</a><span
                                                    class="text-sm font-light text-gray-700">Created 23 Posts</span></p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="px-8 mt-10">
                                <h1 class="mb-4 text-xl font-bold text-gray-700">Categories</h1>
                                <div class="flex flex-col max-w-sm px-4 py-6 mx-auto bg-white rounded-lg shadow-md">
                                    <ul>
                                        @forelse ($tags as $tag)
                                        <li>
                                            <a href="{{ route('post.tag', $tag) }}" class="mx-1 font-bold text-gray-700 hover:text-gray-600 hover:underline">-
                                                {{ $tag->name }}
                                            </a>
                                        </li>
                                        @empty
                                        Vide
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                            <div class="px-8 mt-10">
                                <h1 class="mb-4 text-xl font-bold text-gray-700">Recent Post</h1>
                                <div class="flex flex-col max-w-sm px-8 py-6 mx-auto bg-white rounded-lg shadow-md">
                                    <div class="flex items-center justify-center"><a href="#"
                                            class="px-2 py-1 text-sm text-green-100 bg-gray-600 rounded hover:bg-gray-500">Laravel</a>
                                    </div>
                                    <div class="mt-4"><a href="#" class="text-lg font-medium text-gray-700 hover:underline">Build
                                            Your New Idea with Laravel Freamwork.</a></div>
                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center"><img
                                                src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=731&amp;q=80"
                                                alt="avatar" class="object-cover w-8 h-8 rounded-full"><a href="#"
                                                class="mx-3 text-sm text-gray-700 hover:underline">Alex John</a></div><span
                                            class="text-sm font-light text-gray-600">Jun 1, 2020</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
