@extends('layouts.app', [
    'breadcrumbs' => [
        ['route' => 'delivery-areas.index', 'name' => 'Delivery Area', 'params' => null],
    ]
])

@section('title', 'Edit Delivery Area')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('delivery-areas.update', $deliveryArea) }}">
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ $deliveryArea->code }}" class="form-control @error('code') is-invalid @enderror" id="code" required>
                                @error('code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ $deliveryArea->name }}" class="form-control @error('name') is-invalid @enderror" id="name" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" class="form-control" id="address" cols="30" rows="2">{{ $deliveryArea->address }}</textarea>
                                @error('address')
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
        </div>
    </section>
@endsection
