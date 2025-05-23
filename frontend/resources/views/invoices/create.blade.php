<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Invoice
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('vendors.show', $vendorId) }}"
                class="bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">
                Back</a>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-2 mx-5 flex justify-center items-center ">

                    <form action="{{ route('invoices.store')  }}" method="POST">
                        @csrf
                        <div>
                            <div class="invoice-row mb-4">

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" min="1"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                </div>
                                <div class=" mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Rate (per unit)</label>
                                    <input type="number" step="0.01" name="rate"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Issue Date</label>
                                    <input type="date" name="issue_date"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Payment Terms</label>
                                    <select name="payment_terms"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                        <option value="Net 7">Net 7 days</option>
                                        <option value="Net 14">Net 14 days</option>
                                        <option value="Net 30">Net 30 days</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Description</label>
                                    <textarea name="description"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"></textarea>
                                </div>
                            </div>
                        </div>


                        <!-- Hidden Fields -->
                        <input type="hidden" id="vendorId" name="vendorId" value="{{$vendorId}}">
                        <input type="hidden" id="corporateId" name="corporateId" value="{{$corporateId}}">


                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Invoices</button>
                    </form>


                </div>
            </div>
        </div>
    </div>


</x-app-layout>