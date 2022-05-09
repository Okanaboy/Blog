<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <nav class="bg-grey-light rounded-md w-full my-5 ml-5 text-lg font-semibold">
            <ol class="list-reset flex">
              <li><a href="{{ route('post.index') }}" class="text-blue-600 hover:text-blue-700">Posts</a></li>
              <li><span class="text-gray-500 mx-2">></span></li>
              <li class="text-gray-500">Details user</li>
            </ol>
        </nav>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <ul>
                        <li>Name : {{ $user->name }}</li>
                        <li>Email : {{ $user->email }}</li>
                    </ul>
                </div>
                <span class="p-5">
                    Member since <i>{{ $user->created_at }}</i>
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
