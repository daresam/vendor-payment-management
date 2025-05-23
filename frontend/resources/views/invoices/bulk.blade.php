<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Bulk Invoice
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('vendors.index') }}" class="bg-gray-50 dark:bg-gray-700 text-white px-4 py-2 rounded">
                Back</a>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-5 mx-5 flex justify-center items-center ">

                    <form action="{{ route('store.bulkInvoice', $corporateId)  }}" method="POST">
                        @csrf
                        <div id="invoices-container">
                            <div class="invoice-row mb-4">
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Vendor</label>
                                    <select
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        name="invoices[0][vendor_id]" required>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Quantity</label>
                                    <input type="number"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        name="invoices[0][quantity]" min="1"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"" required>
                                </div>
                                <div class=" mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Rate (per unit)</label>
                                    <input type="number" step="0.01" name="invoices[0][rate]"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Issue Date</label>
                                    <input type="date" name="invoices[0][issue_date]"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Payment Terms</label>
                                    <select name="invoices[0][payment_terms]"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"
                                        required>
                                        <option value="Net 7">Net 7 days</option>
                                        <option value="Net 14">Net 14 days</option>
                                        <option value="Net 30">Net 30 days</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700">Description</label>
                                    <textarea name="invoices[0][description]"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" id="corporateId" name="corporateId" value="{{$corporateId}}">
                        <button type="button" onclick="addInvoiceRow()"
                            class="bg-green-500 text-white px-4 py-2 rounded">Add Another
                            Invoice</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Invoices</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let invoiceCount = 1;
        function addInvoiceRow() {
            const container = document.getElementById('invoices-container');
            const newRow = document.createElement('div');
            newRow.className = 'invoice-row mb-4';
            newRow.innerHTML = `
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700"
>Vendor</label>
                    <select name="invoices[${invoiceCount}][vendor_id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"" required>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700"
>Quantity</label>
                    <input type="number" name="invoices[${invoiceCount}][quantity]" min="1" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"" required>
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700"
>Rate (per unit)</label>
                    <input type="number" step="0.01" name="invoices[${invoiceCount}][rate]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"" required>
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700"
>Issue Date</label>
                    <input type="date" name="invoices[${invoiceCount}][issue_date]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"" required>
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700"
>Payment Terms</label>
                    <select name="invoices[${invoiceCount}][payment_terms]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border"" required>
                        <option value="Net 7">Net 7 days</option>
                        <option value="Net 14">Net 14 days</option>
                        <option value="Net 30">Net 30 days</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-sm text-gray-700"
>Description</label>
                    <textarea name="invoices[${invoiceCount}][description]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full p-2 border""></textarea>
                </div>
            `;
            container.appendChild(newRow);
            invoiceCount++;
        }
    </script>

</x-app-layout>