@extends('layouts.app')

@section('title', 'Order')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" id="requestForm" method="POST" action="{{ route('orders.store') }}">
                            @csrf
                            <div class="col-sm-6">
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
                            <div class="col-sm-6">
                                <label for="inbound_code" class="form-label">Code<span
                                        class="text-danger">*</span></label>
                                <input type="text" value="{{ $inbound_code }}" name="inbound_code" class="form-control @error('inbound_code') is-invalid @enderror" id="inbound_code"
                                     readonly>
                                @error('inbound_code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            {{-- <div class="col-12">
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
                            </div> --}}

                            {{-- <div class="col-sm-6">
                                <label for="sender_name" class="form-label">Sender Name<span
                                        class="text-danger">*</span></label>
                                <input type="sender_name" name="sender_name" class="form-control @error('sender_name') is-invalid @enderror" id="sender_name"
                                     required>
                                @error('sender_name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label for="vehicle_number" class="form-label">Vehicle Number<span
                                        class="text-danger">*</span></label>
                                <input type="vehicle_number" name="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" id="vehicle_number"
                                     required>
                                @error('vehicle_number')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div> --}}

                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" cols="30" rows="3"></textarea>
                                @error('description')
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
                                                <th>Warehouse</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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
                    <select name="request_item_id[]" class="form-select" required onchange="setItemWarehouse(this, event)">
                        <option value="" selected disabled>Choose...</option>
                        @foreach ($goods as $item)
                            <option value="{{ $item->id }}" data-warehouse="{{ $item->warehouse->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="request_item_qty[]" class="form-control" required onchange="calculateSubtotal(this, event)"></td>
                <td><input type="text" name="warehouse[]" class="form-control" disabled readonly></td>
                <td><a href="#" class="btn btn-danger" onclick="removeRow(this, event)">Remove</a></td>
            </tr>
        `;
        requestTable.insertAdjacentHTML('beforeend', newRow);
    });

    const setItemWarehouse = (el, e) => {
        const warehouse = el.options[el.selectedIndex].getAttribute('data-warehouse');
        const row = e.target.closest('tr');
        row.querySelector('input[name="warehouse[]"]').value = warehouse;

        // Update or add item to data array
        const item_id = row.querySelector('select[name="request_item_id[]"]').value;
        const existingItemIndex = data.findIndex(item => item.item_id === item_id);
        if (existingItemIndex !== -1) {
            data[existingItemIndex] = { item_id, warehouse };
        } else {
            data.push({ item_id, warehouse });
        }
    };

    const calculateSubtotal = (el, e) => {
        const qty = e.target.value;
        const row = e.target.closest('tr');
        const warehouse = row.querySelector('input[name="warehouse[]"]').value;
        const item_id = row.querySelector('select[name="request_item_id[]"]').value;
        const existingItemIndex = data.findIndex(item => item.item_id === item_id);
        data[existingItemIndex] = { item_id, qty, warehouse };
    };

    const removeRow = (el, e) => {
        const row = e.target.closest('tr');
        const item_id = row.querySelector('select[name="request_item_id[]"]').value;
        const index = data.findIndex(item => item.item_id === item_id);
        data.splice(index, 1);
        row.remove();
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

