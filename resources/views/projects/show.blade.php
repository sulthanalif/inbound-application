@extends('layouts.app', [
    'breadcrumbs' => [
        ['route' => 'projects.index', 'name' => 'Projects', 'params' => null],
    ]
])

@section('title', 'Project Detail')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Project Detail</h5>
                            <div class="d-flex">
                                <a target="_blank" href="{{ route('projects.print', $project) }}" class="btn btn-primary btn-sm mx-2"><i class="bi bi-printer-fill"></i> PDF</a>
                                <a href="{{ route('projects.export', $project) }}" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i> Excel</a>
                            </div>
                        </div>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Project Code</th>
                                    <td>{{ $project->code }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Adress</th>
                                    <td>{{ $project->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">PJ</th>
                                    <td>{{ $project->user->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        <div
                                            class="badge bg-{{ match ($project->status) {
                                                'On Progress' => 'primary',
                                                'Done' => 'success',
                                                default => 'danger',
                                            } }}">
                                            {{ $project->status }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Outbound History</h5>
                        </div>

                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">#</th>
                                    <th scope="col">Date/Code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Payment</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project->outbounds->count() > 0)
                                    @foreach ($project->outbounds->where('is_resend', 0)->sortByDesc('date')->take(5) as $outbound)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                {{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }} <br>
                                                {{ $outbound->code }}
                                            </td>
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
                                                    {{ \Illuminate\Support\Str::limit($outbound->status, 8) }}</div>
                                            </td>
                                            <td>
                                                <div
                                                    class="badge bg-{{ match ($outbound->status_payment) {
                                                        'Unpaid' => 'danger',
                                                        'Paid' => 'success',
                                                        'Partially Paid' => 'warning',
                                                        default => 'danger',
                                                    } }}">
                                                    {{ $outbound->status_payment }}</div>
                                            </td>
                                            <td style="text-align: center">
                                                <a href="{{ route('projects.showOutbound', $outbound) }}"
                                                    class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a>
                                                <a target="_blank" href="{{ route('projects.printOutbound', $outbound) }}"
                                                    class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" align="center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Inbound</h5>

                            <a href="{{ route('projects.return', $project) }}" class="btn btn-primary btn-sm">Return</a>
                        </div>

                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project->inbounds->count() > 0)
                                    @foreach ($project->inbounds as $inbound)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                {{ Carbon\Carbon::parse($inbound->date)->format('d F Y') }} <br>

                                            </td>
                                            <td>
                                                {{ $inbound->code }}
                                            </td>
                                            <td>
                                                <div
                                                class="badge bg-{{ match ($inbound->status) {
                                                    'Pending' => 'primary',
                                                    'Approved' => 'success',
                                                    'Pickup' => 'info',
                                                    'Delivery' => 'warning',
                                                    'Approved to delivery' => 'primary',
                                                    'Success' => 'success',
                                                    default => 'danger',
                                                } }}">
                                                {{ $inbound->status }}</div>
                                            </td>
                                            <td style="text-align: center">
                                                <a href="{{ route('inbounds.show', $inbound) }}"
                                                    class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" align="center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Goods</h5>

                            {{-- <a href="{{ route('projects.return', $project) }}" class="btn btn-primary btn-sm">Return</a> --}}
                        </div>

                        <table class="table">
                            <thead>
                                <tr style="font-size: 15px">
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col" >Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project->outbounds->count() > 0)
                                    @foreach ($outboundGoods as $item)
                                        <tr style="font-size: 12px">
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                {{ $item['code'] }} <br>

                                            </td>
                                            <td>
                                                {{ $item['name'] }}
                                            </td>
                                            <td>
                                                {{ $item['qty'] }}{{ $item['symbol'] }}
                                            </td>
                                            <td>
                                                {{ $item['type'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" align="center">No Data</td>
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
@endpush

@push('scripts')
@endpush
