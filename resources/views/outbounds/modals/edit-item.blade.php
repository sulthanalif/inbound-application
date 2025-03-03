<div class="modal fade" id="basicModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('outbounds.updateRequest', $outbound) }}" method="POST" id="requestForm">
                @csrf
                @method('PUT')
                {{-- @method('PUT') --}}
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <div class="">
                                <label for="select_category" class="form-label">Category</label>
                                <select id="select_category" name="category_id" class="form-select select2"
                                    onchange="populateGoods(this.value, {{ json_encode($categories) }})">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="">
                                <label for="item-select" class="form-label">Goods</label>
                                <select id="item-select" name="item_id" class="form-select select2"
                                    onchange="addItem(this)">
                                    <option value="" selected disabled>Choose...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('outbounds.show', $outbound) }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const requestForm = document.getElementById('requestForm');
            const requestTable = document.getElementById('request-table');
            let data = [];

            const selectCategory = document.getElementById('select_category');
            const selectGoods = document.getElementById('item-select');

            const populateGoods = (categoryId, categories) => {
                const selectedCategory = categories.find(category => category.id == categoryId);

                if (selectedCategory) {
                    selectGoods.innerHTML = '<option value="" selected disabled>Choose...</option>';

                    selectedCategory.goods.forEach(good => {
                        const option = document.createElement('option');
                        option.value = good.id;
                        option.setAttribute('data-code', good.code);
                        option.setAttribute('data-name', good.name);
                        option.setAttribute('data-price', good.price);
                        option.text = `${good.code} | ${good.name}`;
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
                const itemPrice = selectedItem.getAttribute('data-price');
                const itemQty = 1;

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
                            <td><input type="number" name="request_item_qty[]" class="form-control" value="1" required onchange="calculateSubtotal(this)"></td>
                            <td><input type="number" name="request_item_price[]" class="form-control" value="${itemPrice}" disabled readonly></td>
                            <td><input type="number" name="request_item_subtotal[]" class="form-control" disabled readonly></td>
                            <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                        </tr>
                    `;
                    requestTable.insertAdjacentHTML('beforeend', newRow);
                }

                // Initialize Select2 on the newly added select element
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

                // Update total price
                const total_price = data.reduce((total, item) => total + item.subtotal, 0);
                document.getElementById('total_price').value = total_price;
            };

            const removeRow = (el) => {
                const row = el.closest('tr');
                const item_id = row.getAttribute('data-item-id');
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
        });
    </script>
@endpush


