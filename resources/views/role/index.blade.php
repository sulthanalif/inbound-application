@extends('layouts.app')

@section('title', 'Role')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Role Data</h5>

                <div class="flex">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div>

                <!-- Default Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($roles->count() > 0)
                            @foreach ($roles as $role)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $role->name }}</td>
                                    <td align="center">
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route('roles.destroy', $role) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" align="center">No Data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <!-- End Default Table Example -->
                {{ $roles->links('layouts.paginate') }}

            </div>
        </div>
    </section>
@endsection
