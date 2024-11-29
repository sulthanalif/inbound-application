@extends('layouts.app')

@section('title', 'Outbounds')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
              {{-- <h5 class="card-title">Outbound Data</h5> --}}

              <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('outbounds.request') }}" class="btn btn-primary btn-sm mb-3">Request</a>
                {{-- <div class="">
                    <form action="{{ route('outbounds.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" name="search" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div> --}}
              </div>

              <!-- Default Table -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">Code</th>
                    <th scope="col">Project</th>
                    <th scope="col">PT</th>
                    <th scope="col">User</th>
                    <th scope="col">Status</th>
                    <th scope="col" style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($outbounds as $outbound)
                    <tr>
                        <th scope="row">{{ ($outbounds->currentPage() - 1) * $outbounds->perPage() + $loop->iteration }}</th>
                        <td>{{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }}</td>
                        <td>{{ $outbound->code }}</td>
                        <td>{{ $outbound->project->name }}</td>
                        <td>{{ $outbound->company_name ?? '-' }}</td>
                        <td>{{ auth()->user()->name }}</td>
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
              {{-- <!-- End Default Table Example -->
              {{ $outbounds->links('layouts.paginate') }} --}}

            </div>
          </div>
    </section>
@endsection
