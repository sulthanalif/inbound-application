@extends('layouts.app')

@section('title', 'Role')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Role Data</h5>

                {{-- <div class="flex">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div> --}}

                <!-- Default Table -->
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $role->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
@endsection
