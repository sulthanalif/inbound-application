@extends('layouts.app')

@section('title', 'Outbounds')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Outbound Data</h5>

              {{-- <div class="flex">
                <a href="" class="btn btn-primary btn-sm mb-3">Create</a>
              </div> --}}

              <!-- Default Table -->
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Vendor</th>
                    <th scope="col">Status</th>
                    {{-- <th scope="col">Active</th> --}}
                    <th scope="col" style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($outbounds as $outbound)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $outbound->date }}</td>
                        <td>{{ $outbound->vendor->name }}</td>
                        <td>
                            <div class="badge bg-{{ match ($outbound->status) {
                                                'Pending' => 'primary',
                                                'Approved' => 'success',
                                                'Pickup' => 'info',
                                                'Delivery' => 'warning',
                                                'Approved to delivery' => 'primary',
                                                'Success' => 'success',
                                                default => 'danger',
                                            } }}">{{ $outbound->status }}</div>
                        </td>
                        <td align="center">
                            <a href="{{ route('outbounds.show', $outbound) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Default Table Example -->
              {{ $outbounds->links('layouts.paginate') }}

            </div>
          </div>
    </section>
@endsection
