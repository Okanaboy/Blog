<x-admin-layout>
    <div class="py-12">
        <nav class="bg-grey-light rounded-md w-full my-5 ml-5 text-lg font-semibold">
            <ol class="list-reset flex">
              <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700">Dashboard</a></li>
              <li><span class="text-gray-500 mx-2">></span></li>
              <li><a href="{{ route('admin.posts') }}" class="text-blue-600 hover:text-blue-700">Post</a></li>
              <li><span class="text-gray-500 mx-2">></span></li>
              <li class="text-gray-500">{{ $post->title }}</li>
            </ol>
        </nav>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 my-3 shadow-md" role="alert">
                    <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold">{{ session()->get('message') }}</p>
                    </div>
                    </div>
                </div>
            @endif
            @if ($errors->any())
            <div role="alert" class="my-3">
                <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                  Error
                </div>
                <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
              </div>
            @endif
            <div class="w-full">
                <div class="bg-gradient-to-b from-blue-800 to-blue-600 h-96"></div>
                <div class="max-w-5xl mx-auto px-6 sm:px-6 lg:px-8 mb-12">
                    <div class="bg-white w-full shadow rounded p-8 sm:p-12 -mt-72">
                        <p class="text-3xl font-bold leading-7 text-center">Edit post</p>
                        <form action="{{ route('post.update', $post) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="md:flex items-center mt-8">
                                <div class="w-full flex flex-col">
                                    <label class="font-semibold leading-none">Subject</label>
                                    <input type="text" name="title" value="{{ $post->title }}" class="leading-none text-gray-900 p-3 focus:outline-none focus:border-blue-700 mt-4 bg-gray-100 border rounded border-gray-200"/>
                                </div>
                            </div>
                            <div>
                                <div class="w-full flex flex-col mt-8">
                                    <label class="font-semibold leading-none">Description</label>
                                    <textarea type="text" name="content" class="h-40 text-base leading-none text-gray-900 p-3 focus:oultine-none focus:border-blue-700 mt-4 bg-gray-100 border rounded border-gray-200">{{ $post->content }}</textarea>
                                </div>
                            </div>
                            {{-- {{ dd($post->images) }} --}}
                            @if ($post->images)
                            @foreach ($post->images as $img)
                            <p class="mt-4 font-semibold">
                                Select the image(s) you want to delete
                            </p>
                            @break
                            @endforeach
                            <div class="grid gap-12 items-center md:grid-cols-3 my-5">
                                @foreach ($post->images as $image)
                                <div class="block">
                                    <span class="text-gray-700">
                                        <input type="checkbox" name="image_del[]" value="{{ $image->id }}" class="form-checkbox">
                                    </span>
                                      <div>
                                        <label class="inline-flex items-center">
                                            <div class="space-y-4 text-center">
                                                <img class="w-64 h-64 mx-auto object-cover rounded-xl md:w-40 md:h-40 lg:w-64 lg:h-64" src="{{ asset('storage/' . $image->path) }}" alt="woman" loading="lazy" width="640" height="805">
                                            </div>
                                        </label>
                                      </div>
                                  </div>
                                @endforeach
                            </div>
                            @endif
                            <div class="md:flex items-center mt-8">
                                <div class="w-full flex flex-col">
                                    <input type="file" name="images[]" class="leading-none text-gray-900 p-3 focus:outline-none focus:border-blue-700 mt-4 bg-gray-100 border rounded border-gray-200" multiple/>
                                </div>
                            </div>
                            <p class="mt-4 font-semibold">
                                Select the tag(s) you want to delete
                            </p><br>
                            <div class="inline-flex my-5">
                                @foreach ($post->tags as $tag)
                                <div class="block">
                                    <span class="text-gray-700 m-3">
                                        <input type="checkbox" name="tag_del[]" value="{{ $tag->id }}" class="form-checkbox">
                                        {{ $tag->name }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <div class="md:flex items-center mt-8">

                                <div class="mb-4">
                                    <label class="block text-gray-800 text-sm font-bold mb-2" for="pair">
                                        Choose tags:
                                    </label>
                                    <select id="tag" name="tag[]" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" style="width: 100%" data-placeholder="Select one or more tags..." data-allow-clear="false" multiple="multiple" title="Select tag...">
                                        @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center justify-center w-full">
                                <button type="submit" class="mt-9 font-semibold leading-none text-white py-4 px-10 bg-blue-700 rounded hover:bg-blue-600 focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 focus:outline-none">
                                    Edit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
