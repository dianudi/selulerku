<dialog id="customer_selection_modal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <h3 class="font-bold text-lg">Select or Create Customer</h3>

        <!-- Search Section -->
        <div class="py-4">
            <label for="customer-search-input" class="block text-sm font-medium text-gray-700">Search Customer</label>
            <input type="text" id="customer-search-input" placeholder="Type customer name..."
                class="input input-bordered w-full mt-1">
            <div id="customer-search-results" class="mt-2 border border-gray-200 rounded-md max-h-48 overflow-y-auto">
                <!-- Search results will be populated here -->
            </div>
        </div>

        <div class="divider">OR</div>

        <!-- New Customer Form -->
        <div>
            <h4 class="text-md font-bold mb-2">Create New Customer</h4>
            <form id="new-customer-form" action="{{ route('customers.store') }}" method="POST">
                @csrf
                <fieldset class="fieldset mb-2">
                    <legend class="fieldset-legend">Name</legend>
                    <input type="text" name="name" class="input input-bordered w-full" placeholder="Customer Name">
                    <div id="new-customer-error-name" class="text-red-500 text-xs mt-1"></div>
                </fieldset>
                <fieldset class="fieldset mb-2">
                    <legend class="fieldset-legend">Phone Number</legend>
                    <input type="text" name="phone_number" class="input input-bordered w-full"
                        placeholder="08123456789">
                    <div id="new-customer-error-phone_number" class="text-red-500 text-xs mt-1"></div>
                </fieldset>
                <fieldset class="fieldset mb-2">
                    <legend class="fieldset-legend">Address</legend>
                    <input type="text" name="address" class="input input-bordered w-full" placeholder="abcd">
                    <div id="new-customer-error-address" class="text-red-500 text-xs mt-1"></div>
                </fieldset>
                <button type="submit" class="btn btn-secondary w-full mt-2">Create and Select Customer</button>
            </form>
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>