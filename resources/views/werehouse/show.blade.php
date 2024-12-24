@extends('layouts.app', [
 'breadcrumbs' => [
        ['route' => 'warehouses.index', 'name' => 'Warehouses', 'params' => null]
    ]
])

@section('title', 'Warehouse Detail')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Warehouse Detail</h5>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-danger btn-sm ms-auto">Back</a>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Code</th>
                                <td>{{ $warehouse->code }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Name</th>
                                <td>{{ $warehouse->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Address</th>
                                <td>{{ $warehouse->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Areas</h5>
                        <a href="{{ route('areas.create', $warehouse->id) }}" class="btn btn-primary btn-sm ms-auto">Create</a>
                    </div>
                    <table class="table datatable">
                        <thead>
                            <tr>
                               <th>#</th>
                               <th>Code</th>
                               <th>Name</th>
                               <th>Container</th>
                               <th>Rack</th>
                               <th>Number</th>
                               <th style="width: 200px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouse->areas as $area)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $area->code }}</td>
                                    <td>{{ $area->name }}</td>
                                    <td>{{ $area->container }}</td>
                                    <td>{{ $area->rack }}</td>
                                    <td>{{ $area->number }}</td>
                                    <td style="text-align: center">
                                        {{-- <a href="" class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a> --}}
                                        <a href="{{ route('areas.edit', $area) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                        @role('Super Admin')
                                        <a href="{{ route('areas.destroy', $area) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                                            @endrole
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
