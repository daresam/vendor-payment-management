@extends('layouts.app')

@section('content')
    <h1 class="text-2xl mb-4">Create Corporate</h1>
    <form action="{{ route('corporates.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label>Name</label>
            <input type="text" name="name" class="w-full p-2 border" required>
        </div>
        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" class="w-full p-2 border" required>
        </div>
        <div class="mb-4">
            <label>Phone</label>
            <input type="text" name="phone" class="w-full p-2 border">
        </div>
        <div class="mb-4">
            <label>Address</label>
            <textarea name="address" class="w-full p-2 border"></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
    </form>
@endsection