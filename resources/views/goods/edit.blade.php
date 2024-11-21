@extends('layouts.app')

@section('title', 'Edit Goods')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('goods.update', $goods) }}">
                            @csrf
                            @method('PUT')
                            <div class="col-md-3">
                                <label for="inputName5" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputName5" value="{{ $goods->name }}" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="sk" class="form-label">SK<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="sk" class="form-control @error('sk') is-invalid @enderror" id="sk" value="{{ $goods->sk }}" required>
                                @error('sk')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="category_id" class="form-label">Category<span class="text-danger">*</span></label>
                                <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="" disabled>Choose...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $goods->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="warehouse_id" class="form-label">Warehouse<span class="text-danger">*</span></label>
                                <select id="warehouse_id" name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                    <option value="" disabled>Choose...</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ $goods->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->code }} | {{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="lenght" class="form-label">Lenght<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="length" class="form-control @error('length') is-invalid @enderror" id="lenght" value="{{ $goods->length }}" required>
                                @error('length')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="width" class="form-label">Width<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="width" class="form-control @error('width') is-invalid @enderror" id="width" value="{{ $goods->width }}" required>
                                @error('width')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="height" class="form-label">Height<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="height" class="form-control @error('height') is-invalid @enderror" id="height" value="{{ $goods->height }}" required>
                                @error('height')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="weight" class="form-label">Weight<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="weight" class="form-control @error('weight') is-invalid @enderror" id="weight" value="{{ $goods->weight }}" required>
                                @error('weight')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price" value="{{ $goods->price }}" required>
                                @error('price')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="qty" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" id="qty" value="{{ $goods->qty }}" required>
                                @error('qty')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="3">{{ $goods->description }}</textarea>
                                @error('description')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="condition" class="form-label">Condition</label>
                                <textarea name="condition" id="condition" class="form-control @error('condition') is-invalid @enderror" cols="30" rows="3">{{ $goods->condition }}</textarea>
                                @error('condition')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form><!-- End Multi Columns Form -->

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

