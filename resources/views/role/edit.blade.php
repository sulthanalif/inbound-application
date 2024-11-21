@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('roles.update', $role) }}">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ $role->name }}" class="form-control @error('name') is-invalid @enderror" id="name" required>
                                @error('name')
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
