@extends('layouts.app')

@section('title', 'Inbounds')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
              {{-- <h5 class="card-title">Inbound Data</h5> --}}

              @hasrole('Super Admin|Admin Engineer')
                <div class="flex mt-3">
                    {{-- <a href="{{ route('returns.index') }}" class="btn btn-primary btn-sm mb-3">Return</a> --}}
                    <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm mb-3">Order</a>
                </div>
              @endhasrole

              <!-- Default Table -->
              <table class="table" id="inbound-table">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Date</th>
                    <th scope="col">Code</th>
                    <th scope="col">DN</th>
                    <th scope="col">Status</th>
                    <th scope="col">Type</th>
                    {{-- <th scope="col">Active</th> --}}
                    <th scope="col" style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($inbounds as $inbound)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $inbound->date }}</td>
                        <td>{{ $inbound->code }}</td>
                        <td>{{ $inbound->number ?? '-' }}</td>
                        <td>
                            <div class="badge bg-{{ match ($inbound->status) {
                                                'Pending' => 'primary',
                                                'Approved' => 'success',
                                                // 'Pickup' => 'info',
                                                'Delivery' => 'warning',
                                                // 'Approved to delivery' => 'primary',
                                                'Success' => 'success',
                                                default => 'danger',
                                            } }}">{{ $inbound->status }}</div>
                        </td>
                        <td>
                            <div class="badge bg-{{ $inbound->is_return ? 'danger' : 'primary' }}">
                                {{ $inbound->is_return ? 'Problem' : 'Return' }}
                            </div>
                        </td>
                        <td align="center">
                            <a href="{{ route('inbounds.show', $inbound) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Default Table Example -->
              {{-- {{ $inbounds->links('layouts.paginate') }} --}}

            </div>
          </div>
    </section>
@endsection

@push('scripts')
    <script>
        // DataTables initialisation
        var table = $('#inbound-table').DataTable({
            layout: {
                top1Start: {
                    buttons: [
                        'copy',
                        {
                            extend: 'csv',
                            title: 'Inbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Inbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Inbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Inbound Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },

                    ]
                }
            },
        });
    </script>
@endpush
