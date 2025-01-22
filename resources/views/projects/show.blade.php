@extends('layouts.app', [
    'breadcrumbs' => [
        ['route' => 'projects.index', 'name' => 'Projects', 'params' => null],
    ]
])

@section('title', 'Project Detail')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Project Detail</h5>
                            <div class="d-flex justify-content-end items-center">
                                <a target="_blank" href="{{ route('projects.print', $project) }}" class="btn btn-primary btn-sm mx-2"><i class="bi bi-printer-fill"></i> PDF</a>
                                <a href="{{ route('projects.export', $project) }}" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i> Excel</a>
                                @if ($project->status != 'Finished')
                                <a href="{{ route('projects.endProject', $project) }}" class="btn btn-danger btn-sm ms-2 {{ $isReturnable ? '' : 'disabled' }}">End Project</a>
                                <a href="" class="btn btn-success btn-sm mx-2">Next Project</a>
                                @endif
                            </div>
                        </div>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Project Code</th>
                                    <td>{{ $project->code }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Project Date</th>
                                    <td>{{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }} - {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'Not Yet Over' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Adress</th>
                                    <td>{{ $project->address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Project Owner</th>
                                    <td>{{ $project->user->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        <div
                                            class="badge bg-{{ match ($project->status) {
                                                'On Progress' => 'primary',
                                                'Finished' => 'success',
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
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Payment</th>
                                    <th scope="col">Type</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project->outbounds->count() > 0)
                                    @foreach ($project->outbounds()->latest()->get()->take(5) as $outbound)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                {{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }}


                                            </td>
                                            <td>
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
                                            <td>
                                                <div class="badge bg-{{ $outbound->is_resend ? 'warning' : 'primary' }}">
                                                    {{ $outbound->is_resend ? 'Resend' : 'Request' }}
                                                </div>
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
                                        <td colspan="6" align="center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Inbound</h5>

                            @role('Admin Engineer')
                            <a href="{{ route('projects.return', $project) }}" class="btn btn-primary btn-sm">Return</a>
                            @endrole
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Type</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project->inbounds->count() > 0)
                                    @foreach ($project->inbounds()->latest()->get()->take(5) as $inbound)
                                        <tr>
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
                                            <td>
                                                <div class="badge bg-{{ $inbound->is_return ? 'danger' : 'primary' }}">
                                                    {{ $inbound->is_return ? 'Problem' : 'Return' }}
                                                </div>
                                            </td>
                                            <td style="text-align: center">
                                                <a href="{{ route('inbounds.show', $inbound) }}"
                                                    class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a>
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
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Req</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col" >Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project->outbounds->count() > 0)
                                    @foreach ($outboundGoods as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                {{ $item['code'] }} <br>

                                            </td>
                                            <td>
                                                {{ $item['name'] }}
                                            </td>
                                            <td>
                                                {{ $item['req'] }}{{ $item['symbol'] }}
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
