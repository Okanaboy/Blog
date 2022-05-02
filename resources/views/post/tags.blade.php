<x-app-layout>
    <x-slot name="header">
        <h2 class="flex justify-center font-semibold text-xl text-gray-800 leading-tight">
            Tags
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="px-6 py-8">
                        <div class="flex justify-center bg-white py-8 px-8">
                            <ul>
                                @foreach ($tags as $tag)
                                    <a href="{{ route('post.tag', $tag) }}">
                                        <li class="my-2 text-indigo-800 font-bold">{{ $tag->name }}</li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
