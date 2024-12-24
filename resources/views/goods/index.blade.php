@extends('layouts.app')

@section('title', 'Goods')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex items-center justify-content-between mt-3">
                    <a href="{{ route('goods.create') }}" class="btn btn-primary mb-3">Create</a>
                    <div class="d-flex items-center gap-2">

                        <div class="w-full">
                            <select id="category_id" name="category_id"
                                class="form-select select5 @error('category_id') is-invalid @enderror"
                                 required>
                                <option value="all" selected>Category (All)</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <p class="text-danger text-xs mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="w-full">
                            <select id="warehouse_id" name="warehouse_id"
                                class="form-select select5 @error('warehouse_id') is-invalid @enderror"
                                onchange="populateArea(this.value, {{ json_encode($warehouses) }})" required>
                                <option value="all" selected>Warehouse (All)</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->code }} |
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <p class="text-danger text-xs mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="" style="width: 200px;">
                            <select id="area_id" name="area_id" class="form-select select5 @error('area_id') is-invalid @enderror"
                                required>
                                <option value="all" selected>Area (All)</option>

                            </select>
                            @error('area_id')
                                <p class="text-danger text-xs mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="w-full">
                            <a href="{{ route('goods.index') }}" class="btn btn-primary">Clear</a>
                        </div>
                    </div>
                </div>

                <!-- Default Table -->
                <table class="table" id="projects-table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Code</th>
                            <th scope="col">Name</th>
                            <th scope="col">Vendor</th>
                            <th scope="col">Price</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Category</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goods as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->vendor->name }}</td>
                                <td>Rp.{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->qty }}{{ $item->unit->symbol }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->area->warehouse->name }}{{ '-' . $item->area->name . '-' . $item->area->container . '-' . $item->area->rack . '-' . $item->area->number }}
                                </td>

                                <td align="center">
                                    <a href="{{ route('goods.edit', $item) }}" class="btn btn-primary btn-sm"><i
                                            class="bi bi-pencil-fill"></i></a>
                                            @role('Super Admin')
                                            <a href="{{ route('goods.destroy', $item) }}" class="btn btn-danger btn-sm"
                                                data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                                            @endrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // DataTables initialisation
        var table = $('#projects-table').DataTable({
            layout: {
                top1Start: {
                    buttons: [
                        'copy',
                        {
                            extend: 'csv',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },

                    ]
                }
            },
        });
        const selectWarehouse = document.getElementById('warehouse_id');
        const selectArea = document.getElementById('area_id');
        const warehouses = @json($warehouses);

        const populateArea = (warehouseId) => {
            const selectedWarehouse = warehouses.find(warehouse => warehouse.id == warehouseId);
            if (selectedWarehouse != null) {
                selectArea.innerHTML = '<option value="all" selected>Area (All)</option>';

                selectedWarehouse.areas.forEach(area => {
                    const option = document.createElement('option');
                    option.value = area.id;
                    option.text = `${area.code} - ${area.name} - ${area.container} - ${area.rack}`;
                    if (area.id == "{{ request('area_id') }}") {
                        option.selected = true;
                    }
                    selectArea.appendChild(option);
                });
            } else {
                selectArea.innerHTML = '<option value="" selected disabled>Tidak Ada...</option>';
            }
        };

        if (selectWarehouse.value != 'all') {
            populateArea(selectWarehouse.value);
        }

        $('#warehouse_id').on('change', function() {
            var warehouse = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);

            currentParams.delete('area_id');

            if (warehouse === 'all') {
                currentParams.delete('warehouse_id');
            } else {
                currentParams.set('warehouse_id', warehouse);
            }
            window.location.href = "{{ route('goods.index') }}" + '?' + currentParams.toString();
        });

        $('#area_id').on('change', function() {
            var area = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);
            if (area === 'all') {
                currentParams.delete('area_id');
            } else {
                currentParams.set('area_id', area);
            }
            window.location.href = "{{ route('goods.index') }}" + '?' + currentParams.toString();
        });

        $('#category_id').on('change', function() {
            var category = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);
            if (category === 'all') {
                currentParams.delete('category_id');
            } else {
                currentParams.set('category_id', category);
            }
            window.location.href = "{{ route('goods.index') }}" + '?' + currentParams.toString();
        });
        $('document').ready(function() {
            $('.select5').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush
