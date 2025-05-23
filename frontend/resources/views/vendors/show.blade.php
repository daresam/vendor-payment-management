<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Invoices
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="py-5 mx-5  ">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class=" flex flex-row">
                            <a href="{{ route('vendors.index') }}"
                                class="bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">
                                Back</a>
                        </div>

                        <div class=" align-items-start flex  justify-start ">
                            <form action="{{route('filter.invoice', $vendor->id)}}" method="GET" class="mb-4 mt-4">
                                <div class="flex space-x-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Status</label>
                                        <select name="status"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border">
                                            <option value="">All</option>
                                            <option value="OPEN">Open</option>
                                            <option value="CLOSED">Closed</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Due Date From</label>
                                        <input type="date" name="due_date_from"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Due Date To</label>
                                        <input type="date" name="due_date_to"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Overdue</label>
                                        <input type="checkbox" name="overdue" value="1">
                                    </div>
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-5 py-1 rounded">Filter</button>
                                </div>
                            </form>
                        </div>


                        <div class="mt-2 p-5 flex flex-row-reverse">
                            <a href="{{ route('create.bulkInvoice', $vendor->corporate_id) }}"
                                class="bg-gray-50 dark:bg-orange-700 text-white mx-10 px-4 py-2 rounded">Create Bulk
                                Invoice</a>
                            <a href="{{ route('create.invoice', $vendor->id) }}"
                                class="  bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">Create
                                Invoice</a>

                        </div>
                        {{-- Table --}}
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Invoice Number
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Quantity
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Rate
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Amount
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Status
                                            </div>
                                        </th>

                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Due Date
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Overdue
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">
                                                Actions
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            <div class="flex items-center">

                                            </div>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $invoice->invoice_number }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $invoice->quantity }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $invoice->rate }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $invoice->amount }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $invoice->status }}
                                            </td>

                                            <td class="px-6 py-4">
                                                {{ \Carbon\Carbon::parse($invoice->due_date)->format('D, d M Y')  }}
                                            </td>

                                            <td class="px-6 py-4">
                                                {{ $invoice->is_overdue ? 'Yes' : 'No' }}
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <a href="{{ route('show.invoice', ['id' => $invoice->id, 'vendorId' => $invoice->vendor_id]) }}"
                                                    class="font-medium text-orange-600 dark:text-orange-500 hover:underline">View</a>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <a href="{{ route('edit.invoice', ['id' => $invoice->id, 'vendorId' => $invoice->vendor_id]) }}"
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
        </div>
    </div>

</x-app-layout>