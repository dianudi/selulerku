@extends('templates.base')
@section('title', 'Create Expenses')
@section('content')
<div class="w-full">
    <div class="flex relative">
        <x-sidebar />
        <div class="w-full px-2">
            <x-navbar />
            <h1 class="text-xl font-bold text-center md:text-2xl">Create Expenses</h1>
            <form id="expenseForm" action="{{route('expenses.store')}}" class="mx-auto block max-w-lg" method="post">
                @csrf

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Description</legend>
                    <input name="description" type="text" value="{{old('description')}}" class="input w-full"
                        placeholder="Type here" />
                    <div id="description" class="text-red-500 error-message"></div>
                </fieldset>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Category</legend>
                    <input name="category" type="text" value="{{old('category')}}" class="input w-full"
                        placeholder="Type here" />
                    <div id="category" class="text-red-500 error-message"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Amount</legend>
                    <input inputmode="numeric" name="amount" type="text" value="{{old('amount')}}" class="input w-full"
                        placeholder="Type here" />
                    <div id="amount" class="text-red-500 error-message"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Payment Method</legend>
                    <input inputmode="numeric" name="payment_method" type="text" value="{{old('payment_method')}}"
                        class="input w-full" placeholder="Type here" />
                    <div id="payment_method" class="text-red-500 error-message"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Date</legend>
                    <input name="expense_date" type="date" value="{{old('expense_date')}}" class="input w-full"
                        placeholder="Type here" />
                    <div id="expense_date" class="text-red-500 error-message"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Receipt Image</legend>
                    <input type="file" name="receipt_image_path"
                        onchange=" document.getElementById('modalPreviewReceipt').src = window.URL.createObjectURL(this.files[0])"
                        class="file-input w-full" />
                    <label class="label">Max size 1MB</label>
                    <div id="receipt_image_path" class="text-red-500 error-message"></div>
                    <div class="border max-w-24 min-h-24 max-h-24 mb-2">
                        <img id="modalPreviewReceipt" class="w-full h-full object-cover object-center" id="preview"
                            src="" alt="preview">
                    </div>
                </fieldset>

                <button class="btn btn-primary w-full my-3">Create Expense</button>
            </form>
        </div>
    </div>
</div>
@endsection