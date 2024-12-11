@extends('layouts.app')

@section('title', 'Edit Area')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('areas.update', $area) }}">
                            @csrf
                            @method('put')
                            <div class="col-sm-6">
                                <label for="code" class="form-label">Code<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" id="code" value="{{ $area->code }}" required>
                                @error('code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ $area->name }}" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="container" class="form-label">Container</label>
                                <input type="text" name="container" class="form-control @error('container') is-invalid @enderror" id="container" value="{{ $area->container }}">
                                @error('container')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="rack" class="form-label">Rack</label>
                                <input type="text" name="rack" class="form-control @error('rack') is-invalid @enderror" id="rack" value="{{ $area->rack }}">
                                @error('rack')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label for="number" class="form-label">Number</label>
                                <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" id="number" value="{{ $area->number }}">
                                @error('number')
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

