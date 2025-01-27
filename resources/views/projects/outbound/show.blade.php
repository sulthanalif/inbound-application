@extends('layouts.app', [
    'breadcrumbs' => [['route' => 'projects.index', 'name' => 'Projects', 'params' => null], ['route' => 'projects.show', 'params' => $outbound->project, 'name' => 'Project Detail']],
])

@section('title', 'Outbounds Detail')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Outbound Detail</h5>
                            <a target="_blank" href="{{ route('projects.printOutbound', $outbound) }}"
                                class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i> Pdf</a>
                        </div>

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
                                    <td>{{ $outbound->project->user->company ?? '-' }}</td>
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
                               @if (!$outbound->is_resend && !$outbound->move_from)
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
                               @endif
                                @if ($outbound->move_from || $outbound->move_to)
                                <tr>
                                    <th scope="row">Move From</th>
                                    <td>{{ $outbound->move_from ?? '-' }}</td>
                                </tr>
                                @else
                                <tr>
                                    <th scope="row">Pickup Area</th>
                                    <td>{{ $outbound->pickup_area_id == null ? '-' : $outbound->pickupArea->warehouse->name . ' - ' . $outbound->pickupArea->name . ' - ' . $outbound->pickupArea->container . ' - ' . $outbound->pickupArea->rack . ' - ' . $outbound->pickupArea->number }}
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
                                    <th scope="row">Delivery Area</th>
                                    <td>{{ $outbound->deliveryArea->name ?? '-' }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>



                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Items</h5>
                            {{-- @if ($outbound->status == 'Pending')
                                <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary">
                                    Edit
                                </a>
                            @endif --}}
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">No</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    @role('Admin Engineer')
                                    @else
                                        <th scope="col">Warehouse</th>
                                    @endrole
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound->items as $item)
                                    <tr style="font-size: 15px">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->code }}</td>
                                        <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                        @role('Admin Engineer')
                                        @else
                                            <td>{{ $item->goods->warehouseName() }}
                                            </td>
                                        @endrole
                                        <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                        <td>{{ $item->goods->type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            @if (!$outbound->is_resend)
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Inbound Items</h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">No</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    @role('Admin Engineer')
                                    @else
                                        <th scope="col">Warehouse</th>
                                    @endrole
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($inboundItems->count() > 0)
                                    @foreach ($inboundItems as $item)
                                        <tr style="font-size: 15px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->goods->code }}</td>
                                            <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                            @role('Admin Engineer')
                                            @else
                                                <td>{{ $item->goods->warehouseName() }}
                                                </td>
                                            @endrole
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                            <td>{{ $item->goods->type }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" style="font-size: 15px; text-align: center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Inbound Problem Items</h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">No</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    @role('Admin Engineer')
                                    @else
                                        <th scope="col">Warehouse</th>
                                    @endrole
                                    <th scope="col">Quantity</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($inboundItemsProblems->count() > 0)
                                    @foreach ($inboundItemsProblems as $item)
                                        <tr style="font-size: 15px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->goods->code }}</td>
                                            <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                            @role('Admin Engineer')
                                            @else
                                                <td>{{ $item->goods->warehouseName() }}
                                                </td>
                                            @endrole
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                            <td>{{ $item->goods->type }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" style="font-size: 15px; text-align: center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Outbound Resend</h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">No</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    @role('Admin Engineer')
                                    @else
                                        <th scope="col">Warehouse</th>
                                    @endrole
                                    <th scope="col">Quantity</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($outbounItemsdResend))
                                    @foreach ($outbounItemsdResend as $item)
                                        <tr style="font-size: 15px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->goods->code }}</td>
                                            <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                            @role('Admin Engineer')
                                            @else
                                                <td>{{ $item->goods->warehouseName() }}
                                                </td>
                                            @endrole
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                            <td>{{ $item->goods->type }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" style="font-size: 15px; text-align: center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
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
