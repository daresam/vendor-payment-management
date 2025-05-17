<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Invoice
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="py-5 mx-5  ">
            <div class="py-12">
                <div class="mt-2 p-5 flex flex-row">
                    <a href="{{ route('vendors.index') }}"
                        class="bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">
                        Back</a>
                </div>
                <div class="flex justify-center align-items-center">
                    <div
                        class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <button aria-current="true" type="button"
                            class="w-full px-4 py-2 font-medium text-left rtl:text-right text-white bg-blue-700 border-b border-gray-200 rounded-t-lg cursor-pointer focus:outline-none dark:bg-gray-800 dark:border-gray-600">
                            Invoice
                        </button>
                        <button type="button"
                            class="w-full px-4 py-2 font-medium text-left rtl:text-right border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            Invoice Number: {{$invoice->invoice_number}}
                        </button>
                        <button type="button"
                            class="w-full px-4 py-2 font-medium text-left rtl:text-right border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            Amount: {{$invoice->amount}}
                        </button>
                        <button type="button"
                            class="w-full px-4 py-2 font-medium text-left rtl:text-right border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            Status: {{$invoice->status}}
                        </button>
                        <button type="button"
                            class="w-full px-4 py-2 font-medium text-left rtl:text-right border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            Description: {{$invoice->description}}
                        </button>
                        <button type="button"
                            class="w-full px-4 py-2 font-medium text-left rtl:text-right border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                            Due Date: {{\Carbon\Carbon::parse($invoice->due_date)->format('D, d M Y h:i A') }}
                        </button>

                    </div>
                </div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>