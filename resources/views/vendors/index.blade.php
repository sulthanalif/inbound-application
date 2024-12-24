@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Vendor Data</h5>

              <div class="flex">
                <a href="{{ route('vendors.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
              </div>

              <!-- Default Table -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Address</th>
                    <th scope="col" style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($vendors as $vendor)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $vendor->name }}</td>
                        <td>{{ $vendor->email }}</td>
                        <td>{{ Str::limit($vendor->address, 30) }}</td>

                        <td align="center">
                            <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                            @role('Super Admin')
                            <a href="{{ route('vendors.destroy', $vendor) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                                            @endrole
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Default Table Example -->
              {{-- {{ $vendors->links('layouts.paginate') }} --}}

            </div>
          </div>
    </section>
@endsection
