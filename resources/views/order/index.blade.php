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
                            {{-- <input type="text" name="project_id" value="{{ $project->id }}" hidden"> --}}
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
                                <label for="code" class="form-label">Code<span class="text-danger">*</span></label>
                                <input type="text" value="{{ $inbound_code }}" name="code"
                                    class="form-control @error('code') is-invalid @enderror" id="code" readonly>
                                @error('code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="outbound_id" class="form-label">Outbound<span
                                        class="text-danger">*</span></label>
                                <select id="outbound_id" name="outbound_id" class="form-select select2" required
                                    onchange="getItems(this)">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($outbounds as $outbound)
                                        @if ($outbound->status == 'Success')
                                            <option value="{{ $outbound->id }}"
                                                data-items="{{ json_encode($outbound->items->load(['goods.warehouse', 'goods.unit'])) }}">
                                                {{ $outbound->code }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>



                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="return-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th width="15%">Quantity</th>
                                                <th>Unit</th>
                                                <th>Warehouse</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody id="return-table-body" style="display: none;">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                    cols="30" rows="3"></textarea>
                                @error('description')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
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
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });

        const requestForm = document.getElementById('requestForm');
        const returnTable = document.getElementById('return-table');
        let data = [];

        function getItems(el) {
            var items = JSON.parse(el.options[el.selectedIndex].getAttribute('data-items'));
            // console.log(items[0]);
            var html = '';
            items.forEach(item => {
                html += `<tr>
                            <td data-id="${item.goods.id}">${item.goods.code}</td>
                            <td>${item.goods.name}</td>
                            <td><input class="form-control" type="number" name="qty" max="${item.qty}" value="0"></input></td>
                            <td>${item.goods.unit.symbol}</td>
                            <td>${item.goods.warehouse.name}</td>

                        </tr>`;
            });

            document.getElementById('return-table-body').innerHTML = html;
            document.getElementById('return-table-body').style.display = 'table-row-group';
        }


        // Add a hidden input to the form to send the data array
        requestForm.addEventListener('submit', (e) => {
            e.preventDefault();
            data = [];
            const returnTableBody = document.getElementById('return-table-body');
            const rows = returnTableBody.rows;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const inputs = row.getElementsByTagName('input');
                const qty = Number(inputs[0].value);
                if (qty > 0) {
                    data.push({
                        item_id: row.cells[0].getAttribute('data-id'),
                        qty: qty,
                    });
                }
            }

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'data';
            hiddenInput.value = JSON.stringify(data);
            requestForm.appendChild(hiddenInput);
            requestForm.submit();
        });
    </script>
@endpush

