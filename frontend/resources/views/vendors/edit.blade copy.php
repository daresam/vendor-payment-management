<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Vendors
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <div class="py-5 mx-5 flex justify-center items-center ">
                    <form method="POST" action="{{ route('vendors.update', $vendor->id) }}">
                        @csrf
                        @method('PUT')

                        <label for="corporate"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select a
                            Corporate</label>
                        <select name="corporate_id" id="corporate"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


                            @foreach ($corporates as $corporate)
                                <option {{$corporate->id === $vendor->corporate_id ? 'selected' : ''}}
                                    value="{{$corporate->id}}">{{$corporate->name}}</option>
                            @endforeach
                        </select>
                        <!-- Name -->
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                :value="old('name', $vendor->name)" required autofocus autocomplete="name" />

                            <x-input-error :messages="$errors->get('name')" class="mt-2" />

                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $vendor->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input  :value="old('phone', $vendor->phone)" id="phone" class="block mt-1 w-full" type="text" name="phone" autofocus
                                autocomplete="phone" />

                        </div>
                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input  :value="old('address', $vendor->address)" id="address" class="block mt-1 w-full" type="text" name="address" autofocus
                                autocomplete="address" />
                        </div>


                        <div class="flex items-center justify-end mt-4">

                            <a href="{{ route('vendors.index') }}"
                                class="bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">
                                Back</a>
                            <x-primary-button class="ms-4">
                                {{ __('Submit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>