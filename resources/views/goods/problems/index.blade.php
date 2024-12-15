@extends('layouts.app')

@section('title', 'Problem Items')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Problem Items Data</h5>

                {{-- <div class="flex">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div> --}}

                <!-- Default Table -->
                <table class="table" id="problems-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col">Code Outbound</th>
                            <th scope="col">Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Worthy</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($problems as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->goods->code }}</td>
                                <td>{{ $item->outbound->code }}</td>
                                <td>{{ $item->goods->name }}</td>
                                <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                <td>{{ $item->worthy }}{{ $item->goods->unit->symbol }}</td>
                                <td align="center">
                                    @if ($item->qty > 0)
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered{{ '-'.$item->id }}">
                                        <i class="bi bi-pencil-fill"></i>
                                      </button>
                                      <div class="modal fade" id="verticalycentered{{ '-'.$item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Insert qty of item that still functioning</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('problems.updateWorthy', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                  <div class="row mb-3">
                                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                                        {{-- <label for="qty" class="form-label me-2">Qty</label> --}}
                                                        <input type="number" class="form-control" id="qty" name="qty" min="1" max="{{ $item->qty }}" required style="width: 90px;" value="{{ $item->qty }}" required>
                                                        <label for="unit" class="form-label ms-2">{{ $item->goods->unit->symbol }}</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                  <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                          </div>
                                        </div>
                                      </div><!-- End Vertically centered Modal-->
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- End Default Table Example -->
                {{-- {{ $categories->links('layouts.paginate') }} --}}

            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        // DataTables initialisation
        var table = $('#problems-table').DataTable({
            layout: {
                top1Start: {
                    buttons: [
                        'copy',
                        {
                            extend: 'csv',
                            title: 'Item Problem Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Item Problem Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Item Problem Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Item Problem Data',
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
