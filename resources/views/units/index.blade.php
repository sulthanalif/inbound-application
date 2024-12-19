@extends('layouts.app')

@section('title', 'Units')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Units Data</h5>

                <div class="flex">
                    <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div>

                <!-- Default Table -->
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Symbol</th>
                            <th scope="col">Description</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($units->count() > 0)
                            @foreach ($units as $unit)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->symbol }}</td>
                                    <td>{{ $unit->description }}</td>
                                    <td align="center">
                                        <a href="{{ route('units.edit', $unit) }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route('units.destroy', $unit) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
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
                {{-- {{ $units->links('layouts.paginate') }} --}}

            </div>
        </div>
    </section>
@endsection
