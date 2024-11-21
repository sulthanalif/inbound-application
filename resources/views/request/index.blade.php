@extends('layouts.app')

@section('title', 'Request')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" id="requestForm" method="POST" action="{{ route('request-goods.store') }}">
                            @csrf
                            <div class="col-12">
                                <label for="date" class="form-label">Tanggal<span
                                        class="text-danger">*</span></label>
                                <input type="date" value="{{ now()->format('Y-m-d') }}" name="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                     readonly>
                                @error('date')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="vendor_id" class="form-label">Vendor<span class="text-danger">*</span></label>
                                <select id="vendor_id" name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="request-table">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                {{-- <th>Unit</th> --}}
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr></tr>
                                                <td colspan="2"></td>
                                                <td>Total</td>
                                                <td><input type="number" name="total_price" class="form-control" id="total_price" readonly></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="#" class="btn btn-primary" id="add-row">Add Item</a>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const requestForm = document.getElementById('requestForm');
    const requestTable = document.getElementById('request-table');
    const addRowButton = document.getElementById('add-row');
    let data = [];



    addRowButton.addEventListener('click', (e) => {
        e.preventDefault();
        const newRow = `
            <tr>
                <td>
                    <select name="request_item_id[]" class="form-select" required onchange="setItemPrice(this)">
                        <option value="" selected disabled>Choose...</option>
                        @foreach ($goods as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="request_item_qty[]" class="form-control" required onchange="calculateSubtotal(this)"></td>
                <td><input type="number" name="request_item_price[]" class="form-control" disabled readonly></td>
                <td><input type="number" name="request_item_subtotal[]" class="form-control" disabled readonly></td>
                <td><a href="#" class="btn btn-danger" onclick="removeRow(this)">Remove</a></td>
            </tr>
        `;
        requestTable.insertAdjacentHTML('beforeend', newRow);
    });

    const setItemPrice = (el) => {
        const price = el.options[el.selectedIndex].getAttribute('data-price');
        const row = el.parentElement.parentElement;
        row.querySelector('input[name="request_item_price[]"]').value = price;
        calculateSubtotal(row.querySelector('input[name="request_item_qty[]"]'));
    };

    const calculateSubtotal = (el) => {
        const qty = el.value;
        const price = el.parentElement.parentElement.querySelector('input[name="request_item_price[]"]').value;
        const subtotal = qty * price;
        el.parentElement.parentElement.querySelector('input[name="request_item_subtotal[]"]').value = subtotal;

        // Update or add item to data array
        const item_id = el.parentElement.parentElement.querySelector('select[name="request_item_id[]"]').value;
        const existingItemIndex = data.findIndex(item => item.item_id === item_id);
        if (existingItemIndex !== -1) {
            data[existingItemIndex] = { item_id, qty, subtotal };
        } else {
            data.push({ item_id, qty, subtotal });
        }

        // Update total price
        const total_price = data.reduce((total, item) => total + item.subtotal, 0);
        document.getElementById('total_price').value = total_price;
    };



    const removeRow = (el) => {
        const row = el.parentElement.parentElement;
        const item_id = row.querySelector('select[name="request_item_id[]"]').value;
        const index = data.findIndex(item => item.item_id === item_id);
        data.splice(index, 1);
        row.remove();

        // Update total price
        const total_price = data.reduce((total, item) => total + item.subtotal, 0);
        document.getElementById('total_price').value = total_price;
    };

    // Add a hidden input to the form to send the data array
    requestForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'data';
        hiddenInput.value = JSON.stringify(data);
        requestForm.appendChild(hiddenInput);
        requestForm.submit();
    });
</script>

@endpush

