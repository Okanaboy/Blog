<?php use App\Models\User; ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-center text-2xl text-gray-900 font-bold md:text-4xl leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-20 bg-gray-50">
                <div class="container mx-auto px-6 md:px-12 xl:px-32">
                    <div class="mb-16 text-center">
                        <p class="text-gray-600 text-lg text-justify lg:w-full lg:mx-auto first-letter:text-5xl">
                            {{ $post->content }}
                        </p>
                    </div>

                    <div class="grid gap-12 items-center md:grid-cols-3">
                        @foreach ($post->images as $image)
                        <div class="space-y-4 text-center">
                            <img class="w-64 h-64 mx-auto object-cover rounded-xl md:w-40 md:h-40 lg:w-64 lg:h-64"
                                src="{{ asset('storage/' . $image->path) }}" alt="image" loading="lazy" width="640" height="805">
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- related post -->
                <div class="flex justify-center my-5 text-2xl font-semibold"> Related Post </div>
                <main class="py-4">
                    <div class="px-4">
                    <div class="block md:flex justify-between md:-mx-2">
                        @forelse ($related as $relate)
                        <div class="w-full lg:w-1/3 md:mx-2 mb-4 md:mb-0">
                            <div class="bg-white rounded-lg overflow-hidden shadow relative">
                                <div class="p-4 h-auto md:h-40 lg:h-48">
                                    <a href="#" class="block text-blue-500 hover:text-blue-600 font-semibold mb-2 text-lg md:text-base lg:text-lg">
                                       {{ $relate->title }}
                                    </a>
                                    <div class="text-gray-600 text-sm leading-relaxed block md:text-xs lg:text-sm">
                                        {{ Str::limit($relate->content, 100) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty

                        @endforelse
                    </div>
                    </div>
                </main>
                @guest
                <div class="flex justify-center mt-10 w-full bg-white py-10 text-2xl font-bold">
                    <i><a href="{{ route('login') }}" class="mx-3 text-indigo-800">Login</a> before post a comment</i>
                </div>
                @endguest
                @auth
                <div class="flex justify-center mt-10 w-full bg-white py-10 text-2xl font-bold">
                    <form action="{{ route('comment.store', $post) }}" method="post">
                        @csrf

                        <div class="w-full">
                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Your message</label>
                            <textarea id="content" name="content" rows="4" cols="100" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..."></textarea>
                        </div>
                        <div class="flex items-center justify-center w-full">
                            <button type="submit" class="mt-9 leading-none text-white py-2 px-2 bg-blue-700 rounded hover:bg-blue-600 focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 focus:outline-none">
                                post comment
                            </button>
                        </div>
                    </form>
                </div>
                @endauth
                {{-- comment --}}
                <div class="antialiased mx-auto max-w-screen-sm">
                    <h3 class="flex justify-center my-4 text-2xl font-semibold text-gray-900">Comments</h3>
                    <div class="space-y-4">

                        @forelse ($comments as $comment)
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" src="{{ ((User::find($comment->users->id))->avatar_path != NULL) ? asset('storage/' . (User::find($comment->users->id))->avatar_path) : Avatar::create((User::find($comment->users->id))->name)->toBase64() }}" alt="avatar">
                            </div>
                            <div class="flex-1 border rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed bg-slate-200">
                                @if ($comment->users->hasRole('admin'))
                                <strong class="bg-green-400 text-white text-sm p-1 rounded-md"><a href="{{ route('show.user', $comment->users) }}">Administrateur</a></strong>
                                @else
                                <strong>{{ $comment->users->name }}</strong>
                                @endif
                                | <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                <p class="text-sm">
                                    {{ $comment->content }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="flex justify-center text-lg">
                            Soyez le premier à laisser un commentaire
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
