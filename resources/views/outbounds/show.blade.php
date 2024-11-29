@extends('layouts.app')

@section('title', 'Outbounds Detail')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Outbound Detail</h5>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Outbound Code</th>
                                    <td>{{ $outbound->code }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Date</th>
                                    <td>{{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Project Name</th>
                                    <td>{{ $outbound->project->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Project Address</th>
                                    <td>{{ $outbound->project->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Company</th>
                                    <td>{{ $outbound->company_name ?? '-' }}</td>
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
                                <tr>
                                    <th scope="row">Status Payment</th>
                                    <td>
                                        <div
                                            class="badge bg-{{ match ($outbound->status_payment) {
                                                'Unpaid' => 'danger',
                                                'Paid' => 'success',
                                                'Partially Paid' => 'warning',
                                                default => 'danger',
                                            } }}">
                                            {{ $outbound->status_payment }} ({{ $outbound->payment }})</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Driver Name</th>
                                    <td>{{ $outbound->sender_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Vahicle Number</th>
                                    <td>{{ $outbound->vehicle_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Area</th>
                                    <td>{{ $outbound->area->name ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Items</h5>
                            @if ($outbound->status == 'Pending')
                            <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary" >
                                Edit
                            </a>
                            @endif
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Sub Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound->items as $item)
                                    <tr style="font-size: 12px">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->code }}</td>
                                        <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                        <td>{{ $item->goods->warehouse->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                            <tr style="font-size: 12px">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="2" style="font-weight: bold">Total</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->total_price, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Items Inbound</h5>
                            @if ($outbound->status == 'Pending')
                            <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary" >
                                Edit
                            </a>
                            @endif
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Sub Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound->items as $item)
                                    <tr style="font-size: 12px">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->code }}</td>
                                        <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                        <td>{{ $item->goods->warehouse->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                            <tr style="font-size: 12px">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="2" style="font-weight: bold">Total</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->total_price, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Items Yang DIganti</h5>
                            @if ($outbound->status == 'Pending')
                            <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary" >
                                Edit
                            </a>
                            @endif
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Sub Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound->items as $item)
                                    <tr style="font-size: 12px">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->code }}</td>
                                        <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                        <td>{{ $item->goods->warehouse->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                            <tr style="font-size: 12px">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="2" style="font-weight: bold">Total</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->total_price, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Payments</h5>
                            @if ($outbound->status_payment == 'Unpaid' || $outbound->status_payment == 'Partially Paid')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#basicModal2">
                                    Pay
                                </button>
                            @endif
                        </div>

                        @include('outbounds.modals.payment')
                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    {{-- <th scope="col">Code</th> --}}
                                    <th scope="col">Method</th>
                                    <th scope="col">Paid</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($outbound->payments()->first()->date == null)
                                    <td colspan="5" style="font-size: 12px; text-align: center">No Data</td>
                                @else
                                    @foreach ($outbound->payments as $payment)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ Carbon\Carbon::parse($payment->date)->format('d F Y') }}</td>
                                            {{-- <td>{{ $payment->code_payment }}</td> --}}
                                            <td>{{ $payment->payment_method . ($payment->bank ? " ($payment->bank)" : '') }}
                                            </td>
                                            <td>{{ 'Rp. ' . number_format($payment->paid, 0, ',', '.') }}</td>
                                            <td style="text-align: center">
                                                <a href="{{ route('payment.downloadImagePayment', $payment) }}"
                                                    class="btn btn-sm btn-primary" target="_blank"><i
                                                        class="bi bi-card-image"></i></a>
                                                <a class="btn btn-sm btn-secondary" href=""><i
                                                        class="bi bi-printer"></i></a>
                                            </td>
                                        </tr>
                                        {{-- @include('outbounds.modals.image-payment') --}}
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot></tfoot>
                            <tr style="font-size: 12px">
                                {{-- <td></td> --}}
                                {{-- <td></td> --}}
                                <td></td>
                                <td colspan="2" style="font-weight: bold; text-align: center">Total</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->payments->sum('paid'), 0, ',', '.') }}</td>

                            </tr>
                            <tr style="font-size: 12px">
                                {{-- <td></td> --}}
                                {{-- <td></td> --}}
                                <td></td>
                                <td colspan="2" style="font-weight: bold; text-align: center">Remaining Payment</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->total_price - $outbound->payments->sum('paid'), 0, ',', '.') }}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-lg-5">
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
                                <div
                                    class="timeline-icon {{ match ($outbound->status) {
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
                                    } }}
                                    </p>
                                    @if ($outbound->status == 'Rejected')
                                        <p>Reason: {{ $outbound->note->reject }}</p>
                                    @endif
                                    @hasrole('Super Admin|Head Warehouse')
                                        @if ($outbound->status == 'Pending')
                                            <form
                                                action="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Approved']) }}">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="is_approved" type="checkbox"
                                                        id="flexSwitchCheckChecked" value="1" checked>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">
                                                        <span class="badge bg-success">Approve</span>
                                                    </label>
                                                </div>
                                                <textarea id="reason" name="reject" class="form-control" style="display: none;"></textarea>
                                                <button type="submit" class="btn btn-sm btn-primary mt-2">Submit</button>
                                            </form>
                                        @endif
                                    @endhasrole
                                </div>
                            </div>
                            @if ($outbound->status !== 'Rejected')
                                <div class="timeline-item">
                                    <div
                                        class="timeline-icon {{ match ($outbound->status) {
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
                                        } }}
                                        </p>
                                        @hasrole('Super Admin|Admin Warehouse')
                                            @if ($outbound->status == 'Approved')
                                                <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Pickup']) }}"
                                                    class="btn btn-info btn-sm text-white  mb-3">Submit</a>
                                            @endif
                                        @endhasrole
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div
                                        class="timeline-icon {{ match ($outbound->status) {
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
                                        } }}
                                        </p>
                                        @if ($outbound->status == 'Delivery')
                                            <p>Sender Name: {{ $outbound->sender_name }} <br>
                                                Vahicle Number: {{ $outbound->vehicle_number }}
                                            </p>
                                        @endif
                                        @hasrole('Super Admin|Admin Warehouse')
                                            @if ($outbound->status == 'Pickup')
                                                <form class="row g-3 mt-1"
                                                    action="{{ route('outbounds.delivery', $outbound) }}" method="POST">
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
                                                {{-- <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Delivery']) }}" class="btn btn-warning btn-sm text-white  mb-3" >Submit</a> --}}
                                            @endif
                                        @endhasrole
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div
                                        class="timeline-icon {{ match ($outbound->status) {
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
                                        } }}
                                        </p>

                                        @hasrole('Super Admin|Head Warehouse')
                                            @if ($outbound->status == 'Delivery')
                                                <form class="row g-3 mt-1"
                                                    action="{{ route('outbounds.approveDelivery', $outbound) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-sm-12">
                                                        <label for="area_id" class="form-label">Area<span
                                                                class="text-danger">*</span></label>
                                                        <select name="area_id" id="area_id" class="form-select select1"
                                                            required>
                                                            <option value="" selected disabled>Choose...</option>
                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}">
                                                                    {{ $area->code }}|{{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('area_id')
                                                            <p class="text-danger text-xs mt-2">
                                                                {{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>
                                                    <div class="">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-sm  mb-3">Submit</button>
                                                    </div>
                                                </form>
                                                {{-- <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Approved to delivery']) }}" class="btn btn-primary btn-sm  mb-3" >Approve</a> --}}
                                            @endif
                                        @endhasrole

                                        @if ($outbound->status == 'Approved to delivery')
                                            <a href="{{ route('outbounds.downloadInvoiceDelivery', $outbound) }}"
                                                class="btn btn-primary btn-sm  mb-3">Download Invoice Delivery</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div
                                        class="timeline-icon {{ match ($outbound->status) {
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
                                        } }}
                                        </p>
                                        @hasrole('Super Admin|Admin Engineer')
                                            @if ($outbound->status == 'Approved to delivery')
                                                <a href="{{ route('outbounds.changeStatus', [$outbound, 'status' => 'Success']) }}"
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

        .form-select {
            z-index: 1051;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select1').each(function() {
                $(this).select2({
                    placeholder: 'Choose..',
                    theme: 'bootstrap4',
                });
            });
        });

        $('.modal').on('shown.bs.modal', function() {
            $('.select2').select2({
                dropdownParent: $('#basicModal .modal-body'),
                placeholder: 'Choose..',
                    theme: 'bootstrap4',
            });
        });

        const checkbox = document.getElementById('flexSwitchCheckChecked');
        const label = document.querySelector('.form-check-label');
        const textarea = document.getElementById('reason');

        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                label.innerHTML = '<span class="badge bg-danger">Reject</span>';
                textarea.style.display = 'block';
            } else {
                label.innerHTML = '<span class="badge bg-success">Approve</span>';
                textarea.style.display = 'none';
                textarea.value = '';
            }
        });

    </script>
@endpush
