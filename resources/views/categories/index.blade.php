@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Categories Data</h5>

                <div class="flex">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div>

                <!-- Default Table -->
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->count() > 0)
                            @foreach ($categories as $category)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $category->name }}</td>
                                    <td align="center">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route('categories.destroy', $category) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
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
                {{-- {{ $categories->links('layouts.paginate') }} --}}

            </div>
        </div>
    </section>
@endsection
