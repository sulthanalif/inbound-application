@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('vendors.update', $vendor) }}">
                            @csrf
                            @method('put')
                            <div class="col-md-12">
                                <label for="inputName5" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="inputName5" value="{{ $vendor->name }}" autofocus
                                    required>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail5" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="inputEmail5" value="{{ $vendor->email }}" required>
                                @error('email')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Telephone</label><span
                                    class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="inputGroupPrepend2">+62</span>
                                    <input type="number" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror" id="phone"
                                        aria-describedby="inputGroupPrepend2" value="{{ $vendor->phone }}" required>
                                </div>
                                @error('telephone')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="inputAddress5" class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="" cols="30"
                                    rows="3">{{ $vendor->address }}</textarea>
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

