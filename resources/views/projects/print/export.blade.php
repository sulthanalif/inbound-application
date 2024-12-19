<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $project->name }}</title>

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

        .table-outbound th, .table-outbound td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1>Project Detail</h1>
    <table class="header">
        <tbody>
            <tr>
                <th>Project Code</th>
                <th>:</th>
                <td>{{ $project->code }}</td>
            </tr>
            <tr>
                <th>Adress</th>
                <th>:</th>
                <td>{{ $project->address }}</td>
            </tr>
            <tr>
                <th>Project Owner</th>
                <th>:</th>
                <td>{{ $project->user->name }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <th>:</th>
                <td>
                        {{ $project->status }}
                </td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-top: 40px;">Outbound</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 15px">
                <th scope="col">No</th>
                <th scope="col">Date</th>
                <th scope="col">Code</th>
                <th scope="col">Status</th>
                <th scope="col">Payment</th>
                {{-- <th scope="col" style="text-align: center;">Action</th> --}}
            </tr>
        </thead>
        <tbody>
            @if ($project->outbounds->count() > 0)
                @foreach ($project->outbounds->where('is_resend', 0)->sortByDesc('date')->take(5) as $outbound)
                    <tr style="font-size: 12px">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            {{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }} <br>
                        </td>
                        <td>{{ $outbound->code }}</td>
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
                            <div>
                                {{ $outbound->status_payment }}</div>
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

    <h3>Inbound</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 15px">
                <th scope="col">No</th>
                <th scope="col">Date</th>
                <th scope="col">Code</th>
                <th scope="col">Status</th>
                {{-- <th scope="col" style="text-align: center;">Action</th> --}}
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
                            >
                            {{ $inbound->status }}</div>
                        </td>
                        {{-- <td style="text-align: center">
                            <a href="{{ route('inbounds.show', $inbound) }}"
                                class="btn btn-primary btn-sm"><i class="bi bi-eye-fill"></i></a>
                        </td> --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" align="center">No Data</td>
                </tr>
            @endif
        </tbody>

    </table>

    <h3>Goods</h3>
    <table class="table-outbound">
        <thead>
            <tr style="font-size: 15px">
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Qty</th>
                <th scope="col">Unit</th>
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
                            {{ $item['qty'] }}
                        </td>
                        <td>
                            {{ $item['symbol'] }}
                        </td>
                        <td>
                            {{ $item['type'] }}
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
</body>

</html>

