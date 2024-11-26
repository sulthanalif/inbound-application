@extends('layouts.app')

@section('title', 'Inbound Detail')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Inbound Detail</h5>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>{{ $inbound->date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Code</th>
                                    <td>{{ $inbound->code }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        <div
                                            class="badge bg-{{ match ($inbound->status) {
                                                'Pending' => 'primary',
                                                'Approved' => 'success',
                                                // 'Pickup' => 'info',
                                                'Delivery' => 'warning',
                                                // 'Approved to delivery' => 'primary',
                                                'Success' => 'success',
                                                default => 'danger',
                                            } }}">
                                            {{ $inbound->status }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Driver</th>
                                    <td>{{ $inbound->sender_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Vehicle Number</th>
                                    <td>{{ $inbound->vehicle_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Description</th>
                                    <td>{{ $inbound->description }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Items</h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Warehouse</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inbound->items as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->goods->warehouse->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Timeline</h5>

                        {{-- <div class="flex"></div>
                        <a href="{{ route('inbounds.approve', $inbound) }}" class="btn btn-success  mb-3" >Approve</a>
                        <a href="{{ route('inbounds.reject', $inbound) }}" class="btn btn-danger mb-3" >Reject</a>
                        <a href="{{ route('inbounds.index') }}" class="btn btn-secondary mb-3">Back</a>
                      </div> --}}

                        <div class="timeline">
                            <div class="timeline-item">
                                <div
                                    class="timeline-icon {{ match ($inbound->status) {
                                        'Pending' => 'bg-secondary',
                                        'Approved' => 'bg-primary',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-primary',
                                    } }}">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ $inbound->status !== 'Rejected' ? 'Approved' : 'Rejected' }}</h6>
                                    <p>{{ match ($inbound->status) {
                                        'Pending' => 'Waiting...',
                                        'Approved' => 'This order has been approved.',
                                        'Rejected' => 'This order has been rejected.',
                                        default => 'This order has been approved.',
                                    } }}
                                    </p>
                                    @hasrole('Super Admin|Head Warehouse')
                                        @if ($inbound->status == 'Pending')
                                            <a href="{{ route('inbounds.changeStatus', [$inbound, 'status' => 'Approved']) }}"
                                                class="btn btn-success btn-sm  mb-3">Approve</a>
                                            <a href="{{ route('inbounds.changeStatus', [$inbound, 'status' => 'Rejected']) }}"
                                                class="btn btn-danger btn-sm mb-3">Reject</a>
                                        @endif
                                    @endhasrole
                                </div>
                            </div>
                            @if ($inbound->status !== 'Rejected')

                                <div class="timeline-item">
                                    <div
                                        class="timeline-icon {{ match ($inbound->status) {
                                            'Pending' => 'bg-secondary',
                                            'Approved' => 'bg-secondary',
                                            // 'Pickup' => 'bg-secondary',
                                            'Delivery' => 'bg-warning',
                                            default => 'bg-warning',
                                        } }}">
                                        <i class="bi bi-box-seam text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Delivery</h6>
                                        <p>{{ match ($inbound->status) {
                                            'Pending' => 'Waiting...',
                                            'Approved' => 'Waiting...',
                                            // 'Pickup' => 'Waiting...',
                                            'Delivery' => 'This order is ready for delivery.',
                                            default => 'This order is ready for delivery.',
                                        } }}
                                        </p>
                                        @hasrole('Super Admin|Admin Engineering')
                                            @if ($inbound->status == 'Approved')
                                                {{-- <a href="{{ route('inbounds.changeStatus', [$inbound, 'status' => 'Delivery']) }}" class="btn btn-warning btn-sm text-white  mb-3" >Submit</a> --}}
                                                <form class="row g-3 mt-1" action="{{ route('inbounds.delivery', $inbound) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-sm-6">
                                                        <label for="sender_name" class="form-label">Sender Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="sender_name"
                                                            class="form-control @error('sender_name') is-invalid @enderror"
                                                            id="sender_name" required>
                                                        @error('sender_name')
                                                            <p class="text-danger text-xs mt-2">
                                                                {{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="vehicle_number" class="form-label">Vehicle Number<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="vehicle_number"
                                                            class="form-control @error('vehicle_number') is-invalid @enderror"
                                                            id="vehicle_number" required>
                                                        @error('vehicle_number')
                                                            <p class="text-danger text-xs mt-2">
                                                                {{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="submit"
                                                        class="btn btn-warning btn-sm text-white mb-3">Submit</button>
                                                    </div>
                                                </form>
                                            @endif
                                        @endhasrole
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div
                                        class="timeline-icon {{ match ($inbound->status) {
                                            'Pending' => 'bg-secondary',
                                            'Approved' => 'bg-secondary',
                                            // 'Pickup' => 'bg-secondary',
                                            'Delivery' => 'bg-secondary',
                                            // 'Approved to delivery' => 'bg-secondary',
                                            'Success' => 'bg-success',
                                            default => 'bg-success',
                                        } }}">
                                        <i class="bi bi-flag text-white"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Success</h6>
                                        <p>{{ match ($inbound->status) {
                                            'Pending' => 'Waiting...',
                                            'Approved' => 'Waiting...',
                                            // 'Pickup' => 'Waiting...',
                                            'Delivery' => 'Waiting...',
                                            // 'Approved to delivery' => 'Waiting...',
                                            'Success' => 'This order is successfully delivered.',
                                            default => 'This order is successfully delivered.',
                                        } }}
                                        </p>
                                        @hasrole('Super Admin|Admin Warehouse')
                                            @if ($inbound->status == 'Delivery')
                                                <a href="{{ route('inbounds.changeStatus', [$inbound, 'status' => 'Success']) }}"
                                                    class="btn btn-success btn-sm  mb-3">Submit</a>
                                            @endif
                                        @endhasrole
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
            list-style: none;
        }

        .timeline-item {
            position: relative;
            padding: 10px 0;
            margin-left: 30px;
        }

        .timeline-icon {
            position: absolute;
            left: -10px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .timeline-content {
            margin-left: 30px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
