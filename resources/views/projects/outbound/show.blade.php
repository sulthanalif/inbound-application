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
                            {{-- @if ($outbound->status == 'Pending')
                                <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary">
                                    Edit
                                </a>
                            @endif --}}
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr style="font-size: 12px">
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    {{-- <th scope="col">Sub Price</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outbound->items as $item)
                                    <tr style="font-size: 12px">
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->goods->code }}</td>
                                        <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                        <td>{{ $item->goods->warehouse->name }}</td>
                                        <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                        {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot></tfoot>
                            <tr style="font-size: 12px">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="2" style="font-weight: bold">Total</td>
                                <td style="font-weight: bold">
                                    {{ 'Rp. ' . number_format($outbound->total_price, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Inbound Items</h5>
                            {{-- @if ($outbound->status == 'Pending')
                                <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary">
                                    Edit
                                </a>
                            @endif --}}
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr style="font-size: 12px">
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    {{-- <th scope="col">Sub Price</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if ($outbound->inbound)
                                    @foreach ($outbound->inbound->items as $item)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->goods->code }}</td>
                                            <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                            <td>{{ $item->goods->warehouse->name }}</td>
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                            {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" style="font-size: 12px; text-align: center">No Data</td>
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
                            @if ($outbound->status == 'Success')
                                <a href="{{ route('inbounds.resend', $outbound) }}" class="btn btn-sm btn-primary">
                                    Resend
                                </a>
                            @endif
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr style="font-size: 12px">
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    {{-- <th scope="col">Sub Price</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if ($outbound->inbound)
                                    @foreach ($outbound->inbound->items->where('inbound.is_return', 1) as $item)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->goods->code }}</td>
                                            <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                            <td>{{ $item->goods->warehouse->name }}</td>
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                            {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" style="font-size: 12px; text-align: center">No Data</td>
                                    </tr>
                                @endif
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Outbound Resend</h5>
                            {{-- @if ($outbound->status == 'Pending')
                                <a href="{{ route('outbounds.editItems', $outbound) }}" class="btn btn-primary">
                                    Edit
                                </a>
                            @endif --}}
                        </div>
                        {{-- @include('outbounds.modals.edit-item') --}}
                        <table class="table">
                            <thead>
                                <tr style="font-size: 12px">
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Quantity</th>
                                    {{-- <th scope="col">Sub Price</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if ($outbound->is_resend == 1)
                                    @foreach ($outbound->items->where('outbound.is_resend', 1) as $item)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->goods->code }}</td>
                                            <td>{{ Str::limit($item->goods->name, 12) }}</td>
                                            <td>{{ $item->goods->warehouse->name }}</td>
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                            {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" style="font-size: 12px; text-align: center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
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
