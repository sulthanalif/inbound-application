@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">User Data</h5>

              @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              <div class="flex">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
              </div>

              <!-- Default Table -->
              <table class="table">
                <thead>
                  <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Active</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><p class="text-center">{{ $user->roles[0]->name }}</p></td>
                        <td align="center"><a href="{{ route('users.is_active', $user) }}" class="btn btn-sm {{ $user->is_active ? 'btn-success' : 'btn-danger' }}">{{ $user->is_active ? 'Yes' : 'No' }}</a></td>
                        <td align="center">
                            <a href="" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Default Table Example -->
              {{ $users->links('layouts.paginate') }}

            </div>
          </div>
    </section>
@endsection
