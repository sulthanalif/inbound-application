@extends('layouts.app')

@section('title', 'Outbounds Detail')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Outbound Detail</h5>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>{{ $outbound->date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Vendor</th>
                                    <td>{{ $outbound->vendor->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        <div
                                            class="badge bg-{{ match ($outbound->status) {
                                                'Pending' => 'primary',
                                                'Approved' => 'success',
                                                'Pickup' => 'info',
                                                'Delivery' => 'warning',
                                                'Approved to delivery' => 'primary',
                                                'Success' => 'success',
                                                default => 'danger',
                                            } }}">
                                            {{ $outbound->status }}</div>
                                    </td>
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
                                    <th scope="col">Sub Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound->items as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                            <tr>
                                <td></td>
                                <td colspan="2" style="font-weight: bold">Total</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->total_price, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Timeline</h5>

                      {{-- <div class="flex"></div>
                        <a href="{{ route('outbounds.approve', $outbound) }}" class="btn btn-success  mb-3" >Approve</a>
                        <a href="{{ route('outbounds.reject', $outbound) }}" class="btn btn-danger mb-3" >Reject</a>
                        <a href="{{ route('outbounds.index') }}" class="btn btn-secondary mb-3">Back</a>
                      </div> --}}

                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon {{ match ($outbound->status) {
                                                'Pending' => 'bg-secondary',
                                                'Approved' => 'bg-primary',
                                                'Rejected' => 'bg-danger',
                                                default => 'bg-primary',
                                            } }}">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ $outbound->status !== 'Rejected' ? 'Approved' : 'Rejected' }}</h6>
                                    <p>{{ match ($outbound->status) {
                                        'Pending' => 'Waiting...',
                                        'Approved' => 'This order has been approved.',
                                        'Rejected' => 'This order has been rejected.',
                                        default => 'This order has been approved.',
                                    } }}</p>
                                    @hasrole('Super Admin|Head Warehouse')
                                        @if ($outbound->status == 'Pending')
                                        <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Approved']) }}" class="btn btn-success btn-sm  mb-3" >Approve</a>
                                        <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Rejected']) }}" class="btn btn-danger btn-sm mb-3">Reject</a>
                                        @endif
                                    @endhasrole
                                </div>
                            </div>
                            @if ($outbound->status !== 'Rejected')
                            <div class="timeline-item">
                                <div class="timeline-icon {{ match ($outbound->status) {
                                                'Pending' => 'bg-secondary',
                                                'Approved' => 'bg-secondary',
                                                'Pickup' => 'bg-info',
                                                default => 'bg-info',
                                            } }}">
                                    <i class="bi bi-truck text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Pickup</h6>
                                    <p>{{ match ($outbound->status) {
                                        'Pending' => 'Waiting...',
                                        'Approved' => 'Waiting...',
                                        'Pickup' => 'This order is ready for pickup.',
                                        default => 'This order is ready for pickup.',
                                    } }}</p>
                                    @hasrole('Super Admin|Admin Warehouse')
                                    @if ($outbound->status == 'Approved')
                                    <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Pickup']) }}" class="btn btn-info btn-sm text-white  mb-3" >Submit</a>
                                    @endif
                                @endhasrole
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon {{ match ($outbound->status) {
                                                'Pending' => 'bg-secondary',
                                                'Approved' => 'bg-secondary',
                                                'Pickup' => 'bg-secondary',
                                                'Delivery' => 'bg-warning',
                                                default => 'bg-warning',
                                            } }}">
                                    <i class="bi bi-box-seam text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Delivery</h6>
                                    <p>{{ match ($outbound->status) {
                                        'Pending' => 'Waiting...',
                                        'Approved' => 'Waiting...',
                                        'Pickup' => 'Waiting...',
                                        'Delivery' => 'This order is ready for delivery.',
                                        default => 'This order is ready for delivery.',
                                    } }}</p>
                                    @hasrole('Super Admin|Admin Warehouse')
                                    @if ($outbound->status == 'Pickup')
                                    <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Delivery']) }}" class="btn btn-warning btn-sm text-white  mb-3" >Submit</a>
                                    @endif
                                @endhasrole
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon {{ match ($outbound->status) {
                                                'Pending' => 'bg-secondary',
                                                'Approved' => 'bg-secondary',
                                                'Pickup' => 'bg-secondary',
                                                'Delivery' => 'bg-secondary',
                                                default => 'bg-primary',
                                            } }}">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Approved to delivery</h6>
                                    <p>{{ match ($outbound->status) {
                                        'Pending' => 'Waiting...',
                                        'Approved' => 'Waiting...',
                                        'Pickup' => 'Waiting..',
                                        'Delivery' => 'Waiting...',
                                        'Approved to delivery' => 'This order has been approved',
                                        default => 'This order has been approved.',
                                    } }}</p>
                                    @hasrole('Super Admin|Head Warehouse')
                                        @if ($outbound->status == 'Delivery')
                                        <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Approved to delivery']) }}" class="btn btn-primary btn-sm  mb-3" >Approve</a>
                                        @endif
                                    @endhasrole
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon {{ match ($outbound->status) {
                                                'Pending' => 'bg-secondary',
                                                'Approved' => 'bg-secondary',
                                                'Pickup' => 'bg-secondary',
                                                'Delivery' => 'bg-secondary',
                                                'Approved to delivery' => 'bg-secondary',
                                                'Success' => 'bg-success',
                                                default => 'bg-success',
                                            } }}">
                                    <i class="bi bi-flag text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Success</h6>
                                    <p>{{ match ($outbound->status) {
                                        'Pending' => 'Waiting...',
                                        'Approved' => 'Waiting...',
                                        'Pickup' => 'Waiting...',
                                        'Delivery' => 'Waiting...',
                                        'Approved to delivery' => 'Waiting...',
                                        'Success' => 'This order is successfully delivered.',
                                        default => 'This order is successfully delivered.',
                                    } }}</p>
                                    @hasrole('Super Admin|Admin Engineer')
                                    @if ($outbound->status == 'Approved to delivery')
                                    <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Success']) }}" class="btn btn-success btn-sm  mb-3" >Submit</a>
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
