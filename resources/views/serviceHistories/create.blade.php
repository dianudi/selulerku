@extends('templates.base')
@section('title', 'Create New Service Histories')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full">
            <x-navbar />
            <div class="w-full px-2">
                <h1 class="text-lg md:text-2xl font-bold text-center">Create New Service History</h1>
                <form id="serviceHistoryForm" action="{{ route('servicehistories.store') }}" class="mx-auto block"
                    method="post">
                    @csrf
                    <div class="flex gap-2 flex-col ">
                        <div class="flex-auto">
                            <h2 class="text-lg font-bold">Service Note</h2>
                            <div class="my-1 text-sm font-bold">Customer</div>
                            <div class="border p-2 rounded-md min-h-[40px] flex items-center justify-between">
                                <span id="selected-customer-name" class="text-gray-500">No customer selected</span>
                                <button type="button" onclick="customer_selection_modal.showModal()" class="btn btn-sm">Select Customer</button>
                            </div>
                            <input type="hidden" name="customer_id" id="customer_id_hidden">
                            <div id="customer_id" class="text-red-500 text-xs mt-1"></div>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Warranty Expired Date</legend>
                                <input name="warranty_expired_at" type="date" value="{{ old('warranty_expired_at') }}"
                                    class="input w-full @error('warranty_expired_at') border-red-500 @enderror"
                                    placeholder="Type here" />
                                <div id="warranty_expired_at" class="text-red-500 text-xs mt-1"></div>
                            </fieldset>
                            <div class="my-1 text-sm font-bold">Status</div>
                            <select class="select w-full @error('status') border-red-500 @enderror" name="status">
                                <option selected value="pending">Pending</option>
                                <option value="on_process">On Process</option>
                                <option value="done">Done</option>
                            </select>
                            <div id="status" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="flex-auto">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-lg font-bold">Service Details</h2>
                                <div id="add-detail-btn" role="button" class="btn btn-sm btn-primary">Add More Details
                                </div>
                            </div>
                            <div id="details-container" class="flex gap-2 flex-col">
                                <!-- Dynamic details will be added here -->
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full mx-auto block md:max-w-xs my-3">Create Service
                        History</button>
                </form>

                <!-- Template for a single detail item -->
                <template id="detail-template">
                    <div class="detail-item mb-2 min-w-full p-4 border rounded-md">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm font-bold">Detail</div>
                            <button type="button" class="remove-detail-btn cursor-pointer hover:text-error text-red-700"><i
                                    class="bi bi-x text-xl"></i></button>
                        </div>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Kind</legend>
                            <input name="details[0][kind]" type="text"
                                class="input w-full"
                                placeholder="Type here" />
                            <div id="details-0-kind" class="text-red-500 text-xs mt-1"></div>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Description</legend>
                            <input name="details[0][description]" type="text"
                                class="input w-full"
                                placeholder="Type here" />
                            <div id="details-0-description" class="text-red-500 text-xs mt-1"></div>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Price</legend>
                            <input name="details[0][price]" type="number"
                                class="input w-full"
                                placeholder="Type here" />
                            <div id="details-0-price" class="text-red-500 text-xs mt-1"></div>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Cost Price</legend>
                            <input name="details[0][cost_price]" type="number"
                                class="input w-full"
                                placeholder="Type here" />
                            <div id="details-0-cost_price" class="text-red-500 text-xs mt-1"></div>
                        </fieldset>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

<x-customer-selection-modal />