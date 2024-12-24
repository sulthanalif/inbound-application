@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                {{-- <h5 class="card-title">Projects Data</h5> --}}

                <div class="d-flex justify-content-between mt-3">
                   <div>
                    @role('Admin Engineer')
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                    @endrole
                   </div>
                    <div class="d-flex align-items-center">
                        {{-- <label for="min" class="me-2">Start Date:</label> --}}
                        <input type="text" class="form-control form-control-sm me-2" id="min" name="min"
                               placeholder="Min Date">
                        {{-- <label for="max" class="me-2">End Date:</label> --}}
                        <input type="text" class="form-control form-control-sm" id="max" name="max"
                               placeholder="Max Date">
                    </div>
                </div>

                <!-- Default Table -->
                <table id="projects-table" class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Code</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">PT</th>
                            <th scope="col">Address</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($projects->count() > 0)
                            @foreach ($projects as $project)
                                <tr>
                                    <th scope="row">
                                        {{ $loop->iteration }}</th>
                                    <td>{{ $project->code }}</td>
                                    <td>{{ Carbon\Carbon::parse($project->start_date)->format('d F Y') }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->user->company ?? '-' }}</td>
                                    <td>{{ $project->address }}</td>
                                    <td><span
                                            class="badge bg-{{ $project->status == 'On Progress' ? 'primary' : 'success' }}">{{ $project->status }}</span>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-primary"><i
                                                class="bi bi-eye-fill"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" align="center">No Data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <!-- End Default Table Example -->
                {{-- {{ $projects->links('layouts.paginate') }} --}}

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var minDate, maxDate;

        // Custom filtering function which will search data in column four between two values
        DataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date(data[2]);

            if (
                (min === null && max === null) ||
                (min === null && date.getTime() <= max.getTime()) ||
                (min.getTime() <= date.getTime() && max === null) ||
                (min.getTime() <= date.getTime() && date.getTime() <= max.getTime())
            ) {
                return true;
            }
            return false;
        });

        // Create date inputs
        minDate = new DateTime('#min', {
            format: 'DD MMMM YYYY'
        });
        maxDate = new DateTime('#max', {
            format: 'DD MMMM YYYY'
        });

        // DataTables initialisation
        var table = $('#projects-table').DataTable({
            layout: {
                top1Start: {
                    buttons: [
                        'copy',
                        {
                            extend: 'csv',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Projects Data',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            }
                        },

                    ]
                }
            },
        });

        // Refilter the table
        $('#min, #max').on('change', function() {
            table.draw();
        });
    </script>
@endpush
