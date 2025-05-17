<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Vendors
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="mt-2 p-5 flex flex-row-reverse">
                    <a href="{{ route('vendors.create') }}"
                        class="bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">Create
                        Vendor</a>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        Email
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        Phone Number
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        Date Created
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        Actions
                                    </div>
                                </th>
                                {{-- <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        
                                    </div>
                                </th> --}}
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendors as $vendor)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $vendor->name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $vendor->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $vendor->phone }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($vendor->created_at)->format('D, d M Y h:i A')  }}
                                    </td>
                                    {{-- <td class="px-8 py-4 text-right">
                                        <a href="{{ route('vendors.show', $vendor->id) }}"
                                            class="font-medium text-orange-600 dark:text-orange-500 hover:underline">View</a>
                                    </td> --}}
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('vendors.edit', $vendor->id) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>