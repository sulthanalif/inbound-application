@extends('layouts.app')

@section('title', 'Delivery Area')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            {{-- <h5 class="card-title">Projects Data</h5> --}}

            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('delivery-areas.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                <div class="d-flex align-items-center">
                </div>
            </div>

            <!-- Default Table -->
            <table id="delivery-area-table" class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Code</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col" style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($deliveryAreas as $area)
                            <tr>
                                <th scope="row">
                                    {{ $loop->iteration }}</th>
                                <td>{{ $area->code }}</td>
                                <td>{{ $area->name }}</td>
                                <td>{{ $area->address }}</td>
                                <td align="center">
                                    <a href="{{ route('delivery-areas.edit', $area) }}" class="btn btn-sm btn-primary"><i
                                            class="bi bi-pencil-fill"></i></a>
                                    <a href="{{ route('delivery-areas.destroy', $area) }}" class="btn btn-sm btn-danger" data-confirm-delete="true"><i
                                            class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

