@extends('layouts.app')

@section('content')
    <h1 class="text-2xl mb-4">Corporates</h1>
    <a href="{{ route('corporates.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create Corporate</a>
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
@endsection