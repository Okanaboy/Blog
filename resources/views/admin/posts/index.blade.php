<x-admin-layout>
                            <div class="container mx-auto px-6 py-8">
                                <h3 class="text-gray-700 text-3xl font-medium">All Posts</h3>
                                <div class="flex flex-col mt-8">
                                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                        <div
                                            class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                                            <table class="min-w-full">
                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                            Title</th>
                                                        <th
                                                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                            Content</th>
                                                        <th
                                                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                            Status</th>

                                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="bg-white">
                                                    @foreach ($posts as $post)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                            <div class="flex items-center">

                                                                <div class="ml-4">
                                                                    <div class="text-sm leading-5 font-medium text-gray-900">
                                                                        {{ $post->title }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                            <div class="text-sm leading-5 text-gray-900">{{ Str::limit($post->content, 200, '...') }}</div>
                                                        </td>

                                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                                            <a href="#" class="text-green-600 hover:text-indigo-900">show</a>
                                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">edit</a>
                                                            <a href="#" class="text-red-600 hover:text-indigo-900">delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
