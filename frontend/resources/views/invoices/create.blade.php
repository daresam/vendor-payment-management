<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Invoice
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-5 mx-5 flex justify-center items-center ">
                    <form method="POST" action="{{ route('invoices.store') }}">
                        @csrf

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount"
                                :value="old('amount')" required autofocus autocomplete="amount" />

                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />

                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                                autofocus autocomplete="description" />
                        </div>

                        <!-- Due Date -->
                        <div class="mt-4">
                            <x-input-label for="date" :value="__('Due Date')" />
                            <input type="date" id="date" name="due_date" value="due_date"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" id="vendorId" name="vendorId" value="{{$vendorId}}">
                        <input type="hidden" id="corporateId" name="corporateId" value="{{$corporateId}}">

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