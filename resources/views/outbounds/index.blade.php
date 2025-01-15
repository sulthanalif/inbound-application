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
              <table class="table" id="outbound-table">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Date</th>
                    <th scope="col">Code</th>
                    <th scope="col">DN</th>
                    <th scope="col">Project</th>
                    <th scope="col">PT</th>
                    <th scope="col">Status</th>
                    <th scope="col">Type</th>
                    <th scope="col" style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($outbounds as $outbound)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }}</td>
                        <td>{{ $outbound->code }}</td>
                        <td>{{ $outbound->number ?? '-' }}</td>
                        <td>{{ $outbound->project->name }}</td>
                        <td>{{ $outbound->project->user->company }}</td>
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
                        <td>
                            <div class="badge bg-{{ $outbound->is_resend ? 'warning' : 'primary' }}">
                                {{ $outbound->is_resend ? 'Resend' : 'Request' }}
                            </div>
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

@push('scripts')
    <script>
        // DataTables initialisation
        var table = $('#outbound-table').DataTable({
            layout: {
                top1Start: {
                    buttons: [
                        'copy',
                        {
                            extend: 'csv',
                            title: 'Outbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Outbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Outbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Outbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },

                    ]
                }
            },
        });
    </script>
@endpush
