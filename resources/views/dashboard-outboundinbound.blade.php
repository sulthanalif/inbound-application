<div class="row w-100">
    <!-- Sales Card -->
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Inbounds</h5>
                </div>

                <table class="table datatable">
                    <thead>
                        <tr style="font-size: 15px">
                            <th scope="col">#</th>
                            <th scope="col">Date/Code</th>
                            <th scope="col">Status</th>
                            {{-- <th scope="col">Payment</th> --}}
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($inbounds->count() > 0)
                            @foreach ($inbounds as $inbound)
                                <tr style="font-size: 12px; text-align: center">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        {{ Carbon\Carbon::parse($inbound->date)->format('d F Y') }} <br>
                                        <a href="{{ route('inbounds.show', $inbound->id) }}" class="badge bg-primary">{{ $inbound->code }}</a>
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
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Outbounds</h5>
                </div>

                <table class="table datatable">
                    <thead>
                        <tr style="font-size: 15px">
                            <th scope="col">#</th>
                            <th scope="col">Date/Code</th>
                            <th scope="col">Status</th>
                            <th scope="col">Payment</th>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($outbounds->count() > 0)
                            @foreach ($outbounds as $outbound)
                                <tr style="font-size: 12px; text-align: center">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        {{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }} <br>
                                        <a href="{{ route('outbounds.show', $outbound->id) }}" class="badge bg-primary">{{ $outbound->code }}</a>
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
                                            {{ $outbound->status }}</div>
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



