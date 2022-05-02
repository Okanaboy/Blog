<?php use App\Models\User; ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-center font-semibold text-xl text-gray-800 leading-tight">
            {{ $tag->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="px-6 py-8">
                        <div class="container flex justify-between mx-auto">
                            <div class="w-full">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
