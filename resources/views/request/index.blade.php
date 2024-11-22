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
                        <form class="row g-3 mt-1" id="requestForm" method="POST"
                            action="{{ route('outbounds.storeRequest') }}">
                            @csrf
                            <div class="col-sm-6">
                                <label for="date" class="form-label">Tanggal<span class="text-danger">*</span></label>
                                <input type="date" value="{{ now()->format('Y-m-d') }}" name="date"
                                    class="form-control @error('date') is-invalid @enderror" id="date" readonly>
                                @error('date')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="code_outbound" class="form-label">Code Outbound<span
                                        class="text-danger">*</span></label>
                                <input type="text" value="{{ $code_outbound }}" name="code_outbound"
                                    class="form-control @error('code_outbound') is-invalid @enderror" id="code_outbound"
                                    readonly>
                                @error('code_outbound')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="project_id" class="form-label">Project<span class="text-danger">*</span></label>
                                <select id="project_id" name="project_id"
                                    class="form-select @error('project_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->code }} | {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <label for="item-select" class="form-label">Goods</label>
                            <div class="col-6">
                                <div class="">
                                    <select id="item-select" name="item_id" class="form-select select2" required onchange="addItem(this)">
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach ($goods as $item)
                                            <option value="{{ $item->id }}" data-code="{{ $item->code }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}">{{ $item->code }} | {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="request-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th width="10%">Quantity</th>
                                                <th width="15%">Price</th>
                                                <th width="15%">Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr></tr>
                                            <td colspan="3"></td>
                                            <td>Total</td>
                                            <td><input type="number" name="total_price" class="form-control"
                                                    id="total_price" readonly></td>
                                            <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
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

        const addItem = (selectElement) => {
            const selectedItem = selectElement.options[selectElement.selectedIndex];
            const itemId = selectedItem.value;
            const itemCode = selectedItem.getAttribute('data-code');
            const itemName = selectedItem.getAttribute('data-name');
            const itemPrice = selectedItem.getAttribute('data-price');

            if (!itemId) return;

            const newRow = `
                <tr>
                    <td>${itemCode}</td>
                    <td>${itemName}</td>
                    <td><input type="number" name="request_item_qty[]" class="form-control" required onchange="calculateSubtotal(this)"></td>
                    <td><input type="number" name="request_item_price[]" class="form-control" value="${itemPrice}" disabled readonly></td>
                    <td><input type="number" name="request_item_subtotal[]" class="form-control" disabled readonly></td>
                    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                </tr>
            `;
            requestTable.insertAdjacentHTML('beforeend', newRow);

            // Initialize Select2 on the newly added select element
            $('select').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        };

        const calculateSubtotal = (el) => {
            const qty = el.value;
            const price = el.parentElement.parentElement.querySelector('input[name="request_item_price[]"]').value;
            const subtotal = qty * price;
            el.parentElement.parentElement.querySelector('input[name="request_item_subtotal[]"]').value = subtotal;

            // Update or add item to data array
            const item_id = el.parentElement.parentElement.querySelector('td:first-child').textContent;
            const existingItemIndex = data.findIndex(item => item.item_id === item_id);
            if (existingItemIndex !== -1) {
                data[existingItemIndex] = {
                    item_id,
                    qty,
                    subtotal
                };
            } else {
                data.push({
                    item_id,
                    qty,
                    subtotal
                });
            }

            // Update total price
            const total_price = data.reduce((total, item) => total + item.subtotal, 0);
            document.getElementById('total_price').value = total_price;
        };

        const removeRow = (el) => {
            const row = el.parentElement.parentElement;
            const item_id = row.querySelector('td:first-child').textContent;
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

        $(document).ready(function() {
            $('select').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush

