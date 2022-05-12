<x-admin-layout>
    <div class="container mx-auto px-6 py-8">
        <h3 class="text-gray-700 text-3xl font-medium">All Users</h3>
        <nav class="bg-grey-light rounded-md w-full my-5 ml-5 text-lg font-semibold">
            <ol class="list-reset flex">
              <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700">Dashboard</a></li>
              <li><span class="text-gray-500 mx-2">></span></li>
              <li class="text-gray-500">Users</li>
            </ol>
        </nav>
        <div class="flex flex-col mt-8">

        <nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded my-2 dark:bg-gray-800">
            <div class="hidden w-full md:block md:w-auto" id="mobile-menu">
                <ul class="flex flex-col mt-4 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium">
                    <li>
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            {{ __('Actif') }}
                        </x-nav-link>
                    </li>
                    <li>
                        <x-nav-link :href="route('admin.users.trashed')" :active="request()->routeIs('admin.users.trashed')">
                            {{ __('Thrased') }}
                        </x-nav-link>
                    </li>
                </ul>
            </div>
        </nav>

            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-max w-full table-auto bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">N°</th>
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Role</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $loop->iteration }}</td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-2" src="{{ ($user->avatar_path != NULL) ? asset('storage/' . $user->avatar_path) : Avatar::create($user->name)->toBase64() }}" alt="">
                                        <span class="font-medium">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs">Active</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center">
                                        {{ ($user->hasRole('admin')) ? 'Admin' : 'User' }}
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <div class="w-4 mr-2 transform hover:text-indigo-500 hover:scale-110">
                                            <a href="{{ route('admin.user.restore', $user->id) }}" title="restore">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                            @if (!$user->hasRole('admin'))
                                            <form action="{{ route('admin.users.forcedestroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="transform hover:text-red-500 hover:scale-110" title="delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
