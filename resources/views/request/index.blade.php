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
                                <label for="project_id" class="form-label ">Project<span
                                        class="text-danger">*</span></label>
                                <select id="project_id" name="project_id"
                                    class="form-select select2 @error('project_id')  is-invalid @enderror" required>
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

                            <div class="col-6">
                                <div class="">
                                    <label for="selext_category" class="form-label">Category</label>
                                    <select id="selext_category" name="category_id" class="form-select select2"
                                        onchange="populateGoods(this.value, {{ json_encode($categories) }})">
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}">
                                                {{ $category->name }}</option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6" id="goods">
                                <div class="">
                                    <label for="item-select" class="form-label">Goods</label>
                                    <select id="item-select" name="item_id" class="form-select select2"
                                        onchange="addItem(this)">
                                        <option value="" selected disabled>Choose...</option>

                                    </select>

                                    {{-- <input type="text" name="other_category" id="other_category" style="display: none"> --}}
                                </div>
                            </div>

                            <div class="col-6" id="other_cat" style="display: none">
                                <div class="">
                                    <label for="other_cat" class="form-label">Goods</label>

                                    <input type="text" name="other_category" id="other_category" class="form-control" >
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
                                                <th width="10%">Unit</th>
                                                <th width="15%">Subtotal</th>
                                                <th width="10%">Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr></tr>
                                            <td colspan="4"></td>
                                            <td>Total</td>
                                            <td><input type="number" name="total_price" class="form-control"
                                                    id="total_price" readonly></td>
                                            <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <label for="payment" class="form-label ">Payment<span class="text-danger">*</span></label>
                                <select id="payment" name="payment"
                                    class="form-select select2 @error('payment')  is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="Full Payment">Full Payment</option>
                                    <option value="Down Payment">Down Payment</option>
                                </select>
                                @error('payment')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            {{-- <div class="col-md-4">
                                <label for="payment_method" class="form-label ">Method<span class="text-danger">*</span></label>
                                <select id="payment_method" name="payment_method"
                                    class="form-select select2 @error('payment_method')  is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="bank" class="form-label ">Bank<span class="text-danger">*</span></label>
                                <select id="bank" name="bank"
                                    class="form-select select2 @error('bank')  is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Bank Mandiri">Bank Mandiri</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BNI">BNI</option>
                                </select>
                                @error('bank')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div> --}}

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

        const selectCategory = document.getElementById('selext_category');
        const selectGoods = document.getElementById('item-select');

        // document.getElementById('selext_category').addEventListener('change', function() {

        // });


        const populateGoods = (categoryId, categories) => {
            const selectedCategory = categories.find(category => category.id == categoryId);

            const input = document.getElementById('other_cat');
            const goodsSelect = document.getElementById('goods');
            // const label = document.querySelector('.form-check-label');

            if (categoryId === 'other') {
                input.style.display = 'block';
                input.required = true;
                goodsSelect.style.display = 'none';
                goodsSelect.required = false;
            } else {
                input.style.display = 'none';
                goodsSelect.style.display = 'block';
                // selectGoods.required = true;
            }


            if (selectedCategory) {
                selectGoods.innerHTML = '<option value="" selected disabled>Choose...</option>';

                selectedCategory.goods.forEach(good => {
                    const option = document.createElement('option');
                    option.value = good.id;
                    option.setAttribute('data-code', good.code);
                    option.setAttribute('data-name', good.name);
                    option.setAttribute('data-price', good.price);
                    option.setAttribute('data-unit-symbol', good.unit?.symbol || '');
                    option.setAttribute('data-type', good.type);
                    option.text = `${good.code} | ${good.name} | ${good.price} | ${good.type}`;
                    selectGoods.appendChild(option);
                });
            } else {
                selectGoods.innerHTML = '<option value="" selected disabled>Choose...</option>';
            }
        };

        const addItem = (selectElement) => {
            const selectedItem = selectElement.options[selectElement.selectedIndex];
            const itemId = selectedItem.value;
            const itemCode = selectedItem.getAttribute('data-code');
            const itemName = selectedItem.getAttribute('data-name');
            const itemPrice = parseFloat(selectedItem.getAttribute('data-price'));
            const itemUnit = selectedItem.getAttribute('data-unit-symbol');
            const itemType = selectedItem.getAttribute('data-type');
            const itemQty = 1;
            const subTotal = itemPrice * itemQty;

            if (!itemId) return;

            const existingRow = document.querySelector(`tr[data-item-id="${itemId}"]`);

            if (existingRow) {
                const qtyInput = existingRow.querySelector('input[name="request_item_qty[]"]');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                calculateSubtotal(qtyInput);
            } else {
                const newRow = `
                    <tr data-item-id="${itemId}">
                        <td>${itemCode}</td>
                        <td>${itemName}</td>
                        <td><input type="number" name="request_item_qty[]" min="1" class="form-control" value="1" required onchange="calculateSubtotal(this)"></td>
                        <td><input type="number" name="request_item_price[]" class="form-control" value="${itemPrice}" disabled readonly></td>
                        <td>${itemUnit}</td>
                        <td><input type="number" name="request_item_subtotal[]" class="form-control" value="${subTotal.toFixed(2)}" disabled readonly></td>
                        <td>${itemType}</td>
                        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                    </tr>
                `;
                requestTable.insertAdjacentHTML('beforeend', newRow);
                data.push({
                    item_id: itemId,
                    qty: itemQty,
                    subtotal: subTotal
                });
                calculateTotal();
            }

            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        };

        const calculateSubtotal = (el) => {
            const row = el.closest('tr');
            const item_id = row.getAttribute('data-item-id');
            const qty = parseInt(el.value);
            const price = parseFloat(row.querySelector('input[name="request_item_price[]"]').value);
            const subtotal = qty * price;

            row.querySelector('input[name="request_item_subtotal[]"]').value = subtotal.toFixed(2);

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
            calculateTotal();
        };

        const calculateTotal = () => {
            const total_price = data.reduce((total, item) => total + item.subtotal, 0);
            document.getElementById('total_price').value = total_price.toFixed(2);
        };

        const removeRow = (el) => {
            const row = el.closest('tr');
            const item_id = row.getAttribute('data-item-id');
            const index = data.findIndex(item => item.item_id === item_id);
            data.splice(index, 1);
            row.remove();
            calculateTotal();
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
            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush
