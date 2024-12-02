@extends('layouts.app')

@section('title', 'Area')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Area Data</h5>

                <div class="flex">
                    <a href="{{ route('areas.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div>

                <!-- Default Table -->
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            {{-- <th scope="col">Status</th> --}}
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($areas->count() > 0)
                            @foreach ($areas as $area)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $area->code }}</td>
                                    <td>{{ $area->name }}</td>
                                    <td>{{ $area->address }}</td>
                                    {{-- <td><span class="badge bg-{{ $area->status == 'On Progress' ? 'primary' : 'success' }}">{{ $area->status }}</span></td> --}}
                                    <td align="center">
                                        <a href="{{ route('areas.edit', $area) }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route('areas.destroy', $area) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" align="center">No Data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <!-- End Default Table Example -->
                {{-- {{ $areas->links('layouts.paginate') }} --}}

            </div>
        </div>
    </section>
@endsection
