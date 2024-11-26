@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Projects Data</h5>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>

                    <div class="search">
                        <form action="{{ route('projects.index') }}" method="GET"></form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
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
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye-fill"></i></a>
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
