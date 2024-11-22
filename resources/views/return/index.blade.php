@extends('layouts.app')

@section('title', 'Return')

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
                                <label for="outbound_id" class="form-label">Outbound<span class="text-danger">*</span></label>
                                <select id="outbound_id" name="outbound_id" class="form-select @error('outbound_id') is-invalid @enderror" required onchange="getItems(this)">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($outbounds as $outbound)
                                        <option value="{{ $outbound->id }}" data-outbound_id="{{ $outbound->id }}" data-items="{{ json_encode($outbound->items) }}">{{ $outbound->code }} | {{ $outbound->project->name }}</option>
                                    @endforeach
                                </select>
                                @error('outbound_id')
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
                                                <th>Warehouse</th>
                                                {{-- <th>Subtotal</th> --}}
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
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
    let data = [];

    const getItems = (el) => {
        const items = JSON.parse(el.selectedOptions[0].dataset.items);
        requestTable.tBodies[0].innerHTML = '';
        items.forEach(item => {
            const row = `
                <tr>
                    <td>${item.item_code} | ${item.item_name}</td>
                    <td><input type="number" name="request_item_qty[]" value="${item.qty || ''}" class="form-control" required></td>
                    <td>${item.warehouse_name || 'Undefined'}</td>
                    <td><a href="#" class="btn btn-danger" onclick="removeRow(this)">Remove</a></td>
                </tr>
            `;
            requestTable.tBodies[0].insertAdjacentHTML('beforeend', row);
        });
    };

    const removeRow = (el) => {
        el.parentElement.parentElement.remove();
    };

    const updateRow = (el) => {
        const row = el.parentElement.parentElement;
        const item_id = row.querySelector('td:first-child').textContent;
        const qty = row.querySelector('input[name="request_item_qty[]"]').value;
        const price = row.querySelector('input[name="request_item_price[]"]').value;
        const subtotal = qty * price;
        row.querySelector('input[name="request_item_subtotal[]"]').value = subtotal;
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

