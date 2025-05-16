<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vendor Payment Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white">
        <a href="{{ route('corporates.index') }}" class="mr-4">Corporates</a>
        {{-- <a href="{{ route('vendors.index') }}" class="mr-4">Vendors</a>
        <a href="{{ route('invoices.index') }}" class="mr-4">Invoices</a>
        <a href="{{ route('logout') }}">Logout</a> --}}
    </nav>
    <div class="container mx-auto p-4">
        @yield('content')
    </div>
</body>

</html>