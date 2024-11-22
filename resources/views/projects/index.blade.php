@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Projects Data</h5>

                <div class="flex">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
                </div>

                <!-- Default Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($projects->count() > 0)
                            @foreach ($projects as $project)
                                <tr>
                                    <th scope="row">{{ ($projects->currentPage() - 1) * $projects->perPage() + $loop->iteration }}</th>
                                    <td>{{ $project->code }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->address }}</td>
                                    <td><span class="badge bg-{{ $project->status == 'On Progress' ? 'primary' : 'success' }}">{{ $project->status }}</span></td>
                                    <td align="center">
                                        <a href=""
                                            class="btn btn-primary btn-sm">Inbound</a>
                                        <a href="" class="btn btn-secondary btn-sm">Next Project</a>
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
                {{ $projects->links('layouts.paginate') }}

            </div>
        </div>
    </section>
@endsection
