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
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-borderless text-center" id="request-table">
                                    <thead>
                                        <tr>
                                            <th>SK</th>
                                            <th>Name</th>
                                            <th width="10%">Quantity</th>
                                            <th width="25%">Price</th>
                                            <th width="25%">Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($outbound->items as $item)
                                            <tr>
                                                <td width="20%">
                                                    <select name="request_item_id[]" class="form-select" required onchange="setItemPrice(this)">
                                                        <option value="" selected disabled>Choose...</option>
                                                        @foreach ($goods as $i)
                                                            <option value="{{ $i->id }}" data-name="{{ $i->name }}" data-price="{{ $i->price }}" {{ $i->id == $item->goods->id ? 'selected' : '' }}>{{ $i->sk }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td width="20%"><input type="text" name="request_item_name[]" class="form-control" value="{{ $item->goods->name }}" readonly></td>
                                                <td><input type="number" name="request_item_qty[]" class="form-control" min="1" required value="{{ $item->qty }}" onchange="calculateSubtotal(this)"></td>
                                                <td><input type="number" name="request_item_price[]" class="form-control" value="{{ number_format($item->goods->price, 0, ',', '') }}" readonly></td>
                                                <td><input type="number" name="request_item_subtotal[]" class="form-control" value="{{ $item->sub_total }}" readonly></td>
                                                <td><a href="#" class="btn btn-danger remove-row" onclick="removeRow(this)">Remove</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>Total</td>
                                            <td><input type="number" name="total_price" class="form-control" value="{{ $outbound->total_price }}" id="total_price" readonly></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="#" class="btn btn-primary" id="add-row">Add Item</a>
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

<script>
    const requestTable = document.getElementById('request-table');
    const addRowButton = document.getElementById('add-row');
    let data = [];

    const setItemPrice = (el) => {
        const price = el.options[el.selectedIndex].getAttribute('data-price');
        const name = el.options[el.selectedIndex].getAttribute('data-name');
        const row = el.closest('tr');
        row.querySelector('input[name="request_item_name[]"]').value = name;
        row.querySelector('input[name="request_item_price[]"]').value = price;
        calculateSubtotal(row.querySelector('input[name="request_item_qty[]"]'));
    };

    const calculateSubtotal = (el) => {
        const qty = el.value;
        const row = el.closest('tr');
        const price = row.querySelector('input[name="request_item_price[]"]').value;
        const subtotal = qty * price;
        row.querySelector('input[name="request_item_subtotal[]"]').value = subtotal;

        const total_price = Array.from(requestTable.querySelectorAll('tbody tr')).reduce((total, row) => {
            const qty = row.querySelector('input[name="request_item_qty[]"]').value;
            const price = row.querySelector('input[name="request_item_price[]"]').value;
            return total + qty * price;
        }, 0);
        document.getElementById('total_price').value = total_price;
    };

    const removeRow = (el) => {
        const row = el.closest('tr');
        row.remove();
        calculateSubtotal(requestTable.querySelector('input[name="request_item_qty[]"]'));
    };

    addRowButton.addEventListener('click', (e) => {
        e.preventDefault();
        const newRow = `
            <tr>
                <td width="20%">
                    <select name="request_item_id[]" class="form-select" required onchange="setItemPrice(this)">
                        <option value="" selected disabled>Choose...</option>
                        @foreach ($goods as $i)
                            <option value="{{ $i->id }}" data-name="{{ $i->name }}" data-price="{{ $i->price }}">{{ $i->sk }}</option>
                        @endforeach
                    </select>
                </td>
                <td width="20%"><input type="text" name="request_item_name[]" class="form-control" readonly></td>
                <td><input type="number" name="request_item_qty[]" class="form-control" min="1" required onchange="calculateSubtotal(this)"></td>
                <td><input type="number" name="request_item_price[]" class="form-control" readonly></td>
                <td><input type="number" name="request_item_subtotal[]" class="form-control" readonly></td>
                <td><a href="#" class="btn btn-danger remove-row" onclick="removeRow(this)">Remove</a></td>
            </tr>
        `;
        requestTable.querySelector('tbody').insertAdjacentHTML('beforeend', newRow);
    });

    document.getElementById('requestForm').addEventListener('submit', (e) => {
        e.preventDefault();
        data = Array.from(requestTable.querySelectorAll('tbody tr')).map(row => {
            return {
                id: row.querySelector('select').value,
                // name: row.querySelector('input[name="request_item_name[]"]').value,
                qty: row.querySelector('input[name="request_item_qty[]"]').value,
                // price: row.querySelector('input[name="request_item_price[]"]').value,
                subtotal: row.querySelector('input[name="request_item_subtotal[]"]').value
            };
        });
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'data';
        hiddenInput.value = JSON.stringify(data);
        e.target.appendChild(hiddenInput);
        e.target.submit();
    });
</script>
