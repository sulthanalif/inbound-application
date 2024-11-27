@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 mt-1" id="requestForm" method="POST"
                            action="{{ route('outbounds.updateRequest', $outbound) }}">
                            @csrf
                            @method('PUT')

                            <!-- Category Selection -->
                            <div class="col-6">
                                <label for="selext_category" class="form-label">Category</label>
                                <select id="selext_category" name="category_id" class="form-select select2"
                                    onchange="populateGoods(this.value, {{ json_encode($categories) }})">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Goods Selection -->
                            <div class="col-6">
                                <label for="item-select" class="form-label">Goods</label>
                                <select id="item-select" class="form-select select2" onchange="addItem(this)">
                                    <option value="" selected disabled>Choose...</option>
                                </select>
                            </div>

                            <!-- Table -->
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="request-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th width="20%">Price</th>
                                                <th width="15%">Quantity</th>
                                                <th>Unit</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($outbound->items as $item)
                                                <tr data-item-id="{{ $item->goods->id }}">
                                                    <td>{{ $item->goods->code }}</td>
                                                    <td>{{ $item->goods->name }}</td>
                                                    <td><input type="number" class="form-control"
                                                            value="{{ $item->goods->price }}" readonly></td>
                                                    <td><input type="number" name="request_item_qty[]" class="form-control"
                                                            value="{{ $item->qty }}" onchange="calculateSubtotal(this)">
                                                    </td>
                                                    <td>{{ $item->goods->unit->symbol }}</td>
                                                    <td><input type="number" class="form-control"
                                                            value="{{ $item->qty * $item->goods->price }}" readonly></td>
                                                    <td><button type="button" class="btn btn-danger"
                                                            onclick="removeRow(this)">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">Total</td>
                                                <td><input type="number" class="form-control" id="total_price" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('outbounds.show', $outbound) }}" class="btn btn-secondary">Back</a>
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

        const populateGoods = (categoryId, categories) => {
            const selectedCategory = categories.find(cat => cat.id == categoryId);
            const goodsSelect = document.getElementById('item-select');
            goodsSelect.innerHTML = '<option value="" disabled selected>Choose...</option>';
            selectedCategory.goods.forEach(good => {
                const option = new Option(`${good.code} | ${good.name}`, good.id);
                option.dataset.code = good.code;
                option.dataset.name = good.name;
                option.dataset.price = good.price;
                option.dataset.unitSymbol = good.unit?.symbol || '';
                goodsSelect.add(option);
            });
        };

        const addItem = (selectElement) => {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const itemId = selectedOption.value;

            const existingRow = document.querySelector(`tr[data-item-id="${itemId}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('input[name="request_item_qty[]"]');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                calculateSubtotal(qtyInput);
                return;
            }

            const newRow = `
            <tr data-item-id="${itemId}">
                <td>${selectedOption.dataset.code}</td>
                <td>${selectedOption.dataset.name}</td>
                <td><input type="number" class="form-control" value="${selectedOption.dataset.price}" readonly></td>
                <td><input type="number" name="request_item_qty[]" class="form-control" value="1" onchange="calculateSubtotal(this)"></td>
                <td>${selectedOption.dataset.unitSymbol}</td>
                <td><input type="number" class="form-control" value="${selectedOption.dataset.price}" readonly></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            </tr>`;
            requestTable.querySelector('tbody').insertAdjacentHTML('beforeend', newRow);
            updateTotalPrice();

            // Initialize Select2 on the newly added select element
            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        };

        const calculateSubtotal = (input) => {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('td:nth-child(3) input').value);
            const qty = parseInt(input.value) || 0;
            const subtotal = price * qty;
            row.querySelector('td:nth-child(6) input').value = subtotal.toFixed(2);
            updateTotalPrice();
        };

        const removeRow = (button) => {
            button.closest('tr').remove();
            updateTotalPrice();
        };

        const updateTotalPrice = () => {
            const subtotals = Array.from(requestTable.querySelectorAll('td:nth-child(6) input')).map(input =>
                parseFloat(input.value) || 0);
            const total = subtotals.reduce((sum, value) => sum + value, 0);
            document.getElementById('total_price').value = total.toFixed(2);
        };

        const collectData = () => {
            const data = [];
            const rows = requestTable.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const itemId = row.getAttribute('data-item-id');
                const qty = parseInt(row.querySelector('input[name="request_item_qty[]"]').value) || 0;
                const subtotal = parseFloat(row.querySelector('td:nth-child(6) input').value) || 0;

                data.push({
                    item_id: itemId,
                    qty: qty,
                    subtotal: subtotal
                });
            });
            return data;
        };

        requestForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const data = collectData();

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'data';
            hiddenInput.value = JSON.stringify(data);
            requestForm.appendChild(hiddenInput);

            requestForm.submit();
        });

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush
