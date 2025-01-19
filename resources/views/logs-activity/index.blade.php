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
                                <td>{{ $log->subject->name ?? $log->subject->code ?? '-'  }}</td>
                                <td>{{ $log->created_at }}</td>
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
