@extends('layouts.app')

@section('title', 'Create Goods')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('goods.store') }}">
                            @csrf
                            <div class="col-md-4">
                                <label for="inputName5" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="inputName5" autofocus
                                    required>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="code" class="form-label">Code<span class="text-danger">*</span></label>
                                <input type="text" name="code"
                                    class="form-control @error('code') is-invalid @enderror" id="code" autofocus
                                    required>
                                @error('code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="type" class="form-label">Type<span class="text-danger">*</span></label>
                                <select id="type" name="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="Rentable">Rentable</option>
                                    <option value="Consumable">Consumable</option>

                                </select>
                                @error('type')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="category_id" class="form-label">Category<span
                                        class="text-danger">*</span></label>
                                <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="warehouse_id" class="form-label">Warehouse<span
                                        class="text-danger">*</span></label>
                                <select id="warehouse_id" name="warehouse_id"
                                    class="form-select @error('warehouse_id') is-invalid @enderror"
                                    onchange="populateArea(this.value, {{ json_encode($warehouses) }})" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->code }} |
                                            {{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="area_id" class="form-label">Area<span class="text-danger">*</span></label>
                                <select id="area_id" name="area_id"
                                    class="form-select @error('area_id') is-invalid @enderror" required>
                                    {{-- <option value="" sel   ected disabled>Choose...</option> --}}

                                </select>
                                @error('area_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="vendor_id" class="form-label">Vendor<span class="text-danger">*</span></label>
                                <select id="vendor_id" name="vendor_id"
                                    class="form-select @error('vendor_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="lenght" class="form-label">Lenght<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" name="length"
                                        class="form-control @error('length') is-invalid @enderror" id="lenght"
                                        aria-describedby="basic-addon1" required>
                                    <span class="input-group-text" id="basic-addon1">cm</span>
                                </div>
                                {{-- <input type="text" class="form-control" placeholder="Recipient's username"
                                aria-label="Recipient's username" aria-describedby="basic-addon2"> --}}
                                @error('length')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="width" class="form-label">Width<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" name="width"
                                        class="form-control @error('width') is-invalid @enderror" id="width"
                                        aria-describedby="basic-addon1" required>
                                    <span class="input-group-text" id="basic-addon1">cm</span>
                                </div>
                                @error('width')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="height" class="form-label">Height<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" name="height"
                                        class="form-control @error('height') is-invalid @enderror" id="height"
                                        aria-describedby="basic-addon1" required>
                                    <span class="input-group-text" id="basic-addon1">cm</span>
                                </div>
                                @error('height')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="weight" class="form-label">Weight<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" name="weight"
                                        class="form-control @error('weight') is-invalid @enderror" id="weight"
                                        aria-describedby="basic-addon1" required>
                                    <span class="input-group-text" id="basic-addon1">kg</span>
                                </div>
                                @error('weight')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="capital" class="form-label">Capital<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" name="capital"
                                        class="form-control @error('capital') is-invalid @enderror" id="capital"
                                        aria-describedby="basic-addon1" required>
                                </div>
                                @error('capital')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" name="price"
                                        class="form-control @error('price') is-invalid @enderror" id="price"
                                        aria-describedby="basic-addon1" required>
                                </div>
                                @error('price')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="qty" class="form-label">Quantity<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="qty"
                                    class="form-control @error('qty') is-invalid @enderror" id="qty" required>
                                @error('qty')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="unit_id" class="form-label">Unit<span class="text-danger">*</span></label>
                                <select id="unit_id" name="unit_id"
                                    class="form-select @error('unit_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} | {{ $unit->symbol }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>


                            <div class="col-sm-6">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                    cols="30" rows="3"></textarea>
                                @error('description')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="customRange2" class="form-label">Condition</label>
                                <div class="d-flex justify-content-between">
                                    <span>Bad</span>
                                    <span>Good</span>
                                </div>
                                <input type="range" class="form-range" min="10" value="100" max="100"
                                    step="10" id="customRange2" name="condition">
                                <div class="d-flex justify-content-between">
                                    <span>10%</span>
                                    <span>20%</span>
                                    <span>30%</span>
                                    <span>40%</span>
                                    <span>50%</span>
                                    <span>60%</span>
                                    <span>70%</span>
                                    <span>80%</span>
                                    <span>90%</span>
                                    <span>100%</span>
                                </div>
                                @error('condition')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form><!-- End Multi Columns Form -->

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const selectWarehouse = document.getElementById('warehouse_id');
        const selectArea = document.getElementById('area_id');

        const populateArea = (warehouseId, warehouses) => {
            const selectedWarehouse = warehouses.find(warehouse => warehouse.id == warehouseId);
            if (selectedWarehouse != null) {
                selectArea.innerHTML = '<option value="" selected disabled>Belum Ada...</option>';
                // console.log(selectedWarehouse.areas);

                selectedWarehouse.areas.forEach(area => {
                    const option = document.createElement('option');
                    option.value = area.id;
                    option.text = `${area.name}`;
                    selectArea.appendChild(option);
                });
            } else {
                selectArea.innerHTML = '<option value="" selected disabled>Tidak Ada...</option>';
            }
        };

        $('document').ready(function() {
            $('select').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush



