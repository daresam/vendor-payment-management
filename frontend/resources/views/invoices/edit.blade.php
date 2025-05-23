<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Invoice Status
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Session Error -->
                @if(session('error'))
                    <p class=" text-red-700 font-bold text-2xl flex justify-center items-center">{{ session('error') }}</p>
                @endif
                <div class="py-5 mx-5 flex justify-center items-center ">

                    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}">
                        @csrf
                        @method('PUT')
                        <label for="corporate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Change Status
                        </label>
                        <select name="status" id="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option {{$invoice->status === 'OPEN' ? 'selected' : ''}} value="OPEN">Open
                            </option>
                            <option {{$invoice->status === 'CLOSED' ? 'selected' : ''}} value="CLOSED">Closed
                            </option>
                        </select>

                        <!-- Hidden Fields -->
                        <input type="hidden" id="vendorId" name="vendorId" value="{{$vendorId}}">
                        <input type="hidden" id="corporateId" name="corporateId" value="{{$corporateId}}">


                        <div class="flex items-center justify-end mt-4">

                            <a href="{{ route('vendors.show', $invoice->vendor_id) }}"
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