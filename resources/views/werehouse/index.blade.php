@extends('layouts.app')

@section('title', 'Warehouse')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Warehouses Data</h5>

                <div class="flex">
                    <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div>

                <!-- Default Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col">Name</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($warehouses->count() > 0)
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <th scope="row">{{ ($warehouses->currentPage() - 1) * $warehouses->perPage() + $loop->iteration }}</th>
                                    <td>{{ $warehouse->code }}</td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td align="center">
                                        <a href="{{ route('warehouses.edit', $warehouse) }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route('warehouses.destroy', $warehouse) }}"
                                            class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
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
                {{ $warehouses->links('layouts.paginate') }}

            </div>
        </div>
    </section>
@endsection