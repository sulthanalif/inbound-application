@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('users.update', $user) }}">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="inputName5" class="form-label">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputName5" autofocus
                                    required value="{{ $user->name }}">
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail5" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail5" required
                                    value="{{ $user->email }}">
                                @error('email')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword5" class="form-label">Update Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword5"
                                    required>
                                @error('password')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" id="nip"
                                    value="{{ $user->nip }}">
                                @error('nip')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="inputAddress5" class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="" cols="30" rows="3">{{ $user->address }}</textarea>
                                @error('address')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ $user->roles[0]->name == $role ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" id="position"
                                    value="{{ $user->position }}">
                                @error('position')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="inputGroupPrepend2">+62</span>
                                    <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" id="validationDefaultUsername"
                                        aria-describedby="inputGroupPrepend2" value="{{ $user->phone }}">
                                </div>
                                @error('phone')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" name="is_active" id="gridCheck"
                                        value="1" {{ $user->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gridCheck">
                                        Active
                                    </label>
                                </div>
                                @error('is_active')
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
