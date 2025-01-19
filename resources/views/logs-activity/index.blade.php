@extends('layouts.app')

@section('title', 'Logs Activity')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body mt-3">

                <!-- Default Table -->
                <table class="table datatable ">
                    <thead>
                        <tr>
                            {{-- <th scope="col">No</th> --}}
                            <th scope="col">Log Name</th>
                            <th scope="col">Causer</th>
                            <th scope="col">Description</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Created At</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                {{-- <th scope="row">{{ $loop->iteration }}</th> --}}
                                <td>{{ $log->log_name }}</td>
                                <td>{{ json_decode($log->causer)->name ?? 'System' }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->subject->name ?? $log->subject->code ?? $log->subject->user->name ?? '-'  }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}</td>
                                <td align="center">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal{{ $log->id }}">
                                        <i class="bi bi-info-circle-fill"></i>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal{{ $log->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Properties</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($log->description == 'User logged in' || $log->description == 'User logged out')
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>IP Address</th>
                                                                <th>User Agent</th>
                                                                <th>Time</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $properties = $log->properties->toArray();
                                                            @endphp
                                                               <tr>
                                                                   <td>{{ $properties['ip_address'] ?? 'Unknown' }}</td>
                                                                   <td>{{ $properties['user_agent'] ?? 'Unknown' }}</td>
                                                                   <td>{{ \Carbon\Carbon::parse($properties['time'] ?? 'Unknown')->format('d-m-Y H:i:s') }}</td>
                                                               </tr>
                                                        </tbody>

                                                    </table>
                                                    @elseif ($log->log_name == 'user_warehouse')
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Warehouse</th>
                                                                <th>Admin ID</th>
                                                                <th>Admin Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $properties = $log->properties->toArray();
                                                            @endphp
                                                               <tr>
                                                                   <td>{{ $properties['warehouse'] ?? 'Unknown' }}</td>
                                                                   <td>
                                                                    @if (array_key_exists('admin_id', $properties))
                                                                        @foreach ($properties['admin_id'] as $adminId)
                                                                            {{ $adminId }}{{ !$loop->last ? ',' : '' }}
                                                                        @endforeach
                                                                    @else
                                                                        Unknown
                                                                    @endif
                                                                   </td>
                                                                   <td>
                                                                    @if (array_key_exists('admin_id_remove', $properties))
                                                                        @if (!empty($properties['admin_id_remove']))
                                                                            @foreach ($properties['admin_id_remove'] as $adminIdRemove)
                                                                                {{ $adminIdRemove }}{{ !$loop->last ? ',' : '' }}
                                                                            @endforeach
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    @else
                                                                        Unknown
                                                                    @endif
                                                                   </td>
                                                               </tr>
                                                        </tbody>

                                                    </table>
                                                    @else
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Field</th>
                                                                <th>Old Value</th>
                                                                <th>New Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $properties = $log->properties->toArray();
                                                                $oldValues = $properties['old'] ?? [];
                                                                $newValues = $properties['attributes'] ?? [];
                                                                $fields = array_keys(array_merge($oldValues, $newValues));
                                                            @endphp
                                                            @foreach ($fields as $field)
                                                               <tr>
                                                                   <td>{{ $field }}</td>
                                                                   <td>{{ $oldValues[$field] ?? '-' }}</td>
                                                                   <td>{{ $newValues[$field] ?? '-' }}</td>
                                                               </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Button trigger modal -->



    </section>
@endsection
