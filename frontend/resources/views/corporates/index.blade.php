<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Corporate
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Corporate
                </div>
                <h1 class="text-2xl mb-4">Corporates</h1>
                <a href="{{ route('corporates.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create
                    Corporate</a>
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($corporates as $corporate)
                            <tr>
                                <td>{{ $corporate->name }}</td>
                                <td>{{ $corporate->email }}</td>
                                <td>
                                    <a href="{{ route('corporates.show', $corporate->id) }}" class="text-blue-500">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>