<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $outbound->code }}</title>

    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            /* text-align: center; */
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .header th {
            text-align: left;
        }

        .table-outbound {
            border-collapse: collapse;
            text-align: center;
            width: 100%;
        }

        .table-outbound th,
        .table-outbound td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1>Outbound Detail</h1>
    <table class="header">
        <tbody>
            <tr>
                <th scope="row">Outbound Code</th>
                <th>:</th>
                <td>{{ $outbound->code }}</td>
            </tr>
            <tr>
                <th scope="row">Date</th>
                <th>:</th>
                <td>{{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }}</td>
            </tr>
            <tr>
                <th scope="row">Project Name</th>
                <th>:</th>
                <td>{{ $outbound->project->name }}</td>
            </tr>
            <tr>
                <th scope="row">Project Address</th>
                <th>:</th>
                <td>{{ $outbound->project->address }}</td>
            </tr>
            <tr>
                <th scope="row">Company</th>
                <th>:</th>
                <td>{{ $outbound->company_name ?? '-' }}</td>
            </tr>
            <tr>
                <th scope="row">Status</th>
                <th>:</th>
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
                <th>:</th>
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
                <th scope="row">Pickup Area</th>
                <th>:</th>
                <td>{{ $outbound->pickup_area_id == null ? '-' : $outbound->pickupArea->warehouse->name . ' - ' . $outbound->pickupArea->name . ' - ' . $outbound->pickupArea->container . ' - ' . $outbound->pickupArea->rack . ' - ' . $outbound->pickupArea->number }}
                </td>
            </tr>
            <tr>
                <th scope="row">Driver Name</th>
                <th>:</th>
                <td>{{ $outbound->sender_name ?? '-' }}</td>
            </tr>
            <tr>
                <th scope="row">Vahicle Number</th>
                <th>:</th>
                <td>{{ $outbound->vehicle_number ?? '-' }}</td>
            </tr>
            <tr>
                <th scope="row">Delivery Area</th>
                <th>:</th>
                <td>{{ $outbound->deliveryArea->name ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-top: 40px;">Items</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 12px">
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Warehouse</th>
                <th scope="col">Quantity</th>
                <th scope="col">Type</th>
                {{-- <th scope="col">Sub Price</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($outbound->items as $item)
                <tr style="font-size: 12px">
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $item->goods->code }}</td>
                    <td>{{ Str::limit($item->goods->name, 20) }}</td>
                    <td>{{ $item->goods->area->warehouse->name }}</td>
                    <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                    <td>{{ $item->goods->type }}</td>
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

    <h3>Inbound Items</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 12px">
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Warehouse</th>
                <th scope="col">Quantity</th>
                <th scope="col">Type</th>
                {{-- <th scope="col">Sub Price</th> --}}
            </tr>
        </thead>
        <tbody>
            @if ($inboundItems->count() > 0)
                @foreach ($inboundItems as $item)
                    <tr style="font-size: 12px">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->goods->code }}</td>
                        <td>{{ Str::limit($item->goods->name, 20) }}</td>
                        <td>{{ $item->goods->area->warehouse->name }}</td>
                        <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                        <td>{{ $item->goods->type }}</td>
                        {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="font-size: 12px; text-align: center">No Data</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h3>Inbound Problem Items</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 12px">
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Warehouse</th>
                <th scope="col">Quantity</th>
                <th>Type</th>
                {{-- <th scope="col">Sub Price</th> --}}
            </tr>
        </thead>
        <tbody>
            @if ($inboundItemsProblems->count() > 0)
                @foreach ($inboundItemsProblems as $item)
                    <tr style="font-size: 12px">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->goods->code }}</td>
                        <td>{{ Str::limit($item->goods->name, 20) }}</td>
                        <td>{{ $item->goods->area->warehouse->name }}</td>
                        <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                        <td>{{ $item->goods->type }}</td>
                        {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="font-size: 12px; text-align: center">No Data</td>
                </tr>
            @endif
    </table>

    <h3>Outbound Resend</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 12px">
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Warehouse</th>
                <th scope="col">Quantity</th>
                <th>Type</th>
                {{-- <th scope="col">Sub Price</th> --}}
            </tr>
        </thead>
        <tbody>
            @if (count($outbounItemsdResend) > 0)
                @foreach ($outbounItemsdResend as $item)
                    <tr style="font-size: 12px">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->goods->code }}</td>
                        <td>{{ Str::limit($item->goods->name, 20) }}</td>
                        <td>{{ $item->goods->area->warehouse->name }}</td>
                        <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                        <td>{{ $item->goods->type }}</td>
                        {{-- <td>{{ 'Rp. ' . number_format($item->sub_total, 0, ',', '.') }}</td> --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="font-size: 12px; text-align: center">No Data</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>
