<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .content {
            margin: 20mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header th {
            border: none;
            padding-bottom: 10px;
        }
        .footer td {
            border: none;

        }
        .footer tr:first-child {
            height: 100px;
        }

        #signature {
            width: 200px;
            height: auto;
        }

    </style>
</head>

<body>
    <div class="content">
        <table class="header">
            <tr>
                <th style="width: 50%">
                    <img src="{{ public_path('images/logo.jpg') }}" width="150" height="50" alt="logo">
                </th>
                <th style="width: 50%; text-align: ">
                    <h1>Delivery Note</h1>
                </th>
            </tr>
            <tr>
                <td>
                    Stockyard KWJJV Package 2 & 3<br>
                    Jl. sunter permai raya depan perumahan t.s.a 2 tanah kosong, RT.2/RW.12, Sunter Agung, Kec. Tj. Priok,
                    Jkt Utara. <br> Daerah Khusus Ibukota Jakarta 14350
                </td>
                <td>
                    No: {{ $outbound->number }} <br>
                    Tanggal: {{ \Carbon\Carbon::parse($outbound->date)->format('d F Y') }} <br>
                    Project: {{ $outbound->project->name }}
                </td>
            </tr>
            {{-- <tr>
                <th colspan="3">
                    <h2 align="center">Delivery Note</h2>
                </th>
            </tr>
            <tr>
                <td rowspan="2">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.jpg'))) }}" width="150" height="50" alt="logo"><br>
                    Stockyard KWJJV Package 2 & 3<br>
                    Jl. sunter permai raya depan perumahan t.s.a 2 tanah kosong, RT.2/RW.12, Sunter Agung, Kec. Tj. Priok,
                    Jkt Utara. <br> Daerah Khusus Ibukota Jakarta 14350
                </td>
                <td rowspan="2">No: {{ $outbound->number }} <br> Tanggal: {{ \Carbon\Carbon::parse($outbound->date)->format('d F Y') }} </td>
            </tr>
            <tr>
                <td colspan="3"><b>To Project: </b>{{ $outbound->project->name }}</td> --}}
        </table>
        <table>
            <tr>
                <td style="width: 30%">
                    <b>Penerima</b><br>
                    <b>Alamat</b><br>
                    <b>No Kendaraan</b><br>
                    <b>Nama Pengendara</b><br>
                    <b>Pickup Area</b><br>
                </td>
                <td>
                    <b>: {{ $outbound->user->name }} ({{ $outbound->user->company ?? '-' }})</b><br>
                    <b>: {{ $outbound->project->address }}</b><br>
                    <b>: {{ $outbound->vehicle_number }}</b><br>
                    <b>: {{ $outbound->sender_name }}</b><br>
                    <b>: {{ $outbound->pickupArea->warehouse->name }} - {{ $outbound->pickupArea->name }} - {{ $outbound->pickupArea->container }} - {{ $outbound->pickupArea->rack }} - {{ $outbound->pickupArea->number }}</b><br>
                </td>
            </tr>
        </table>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 30%">Name</th>
                    <th style="width: 30%">Spec</th>
                    <th style="width: 5%">Qty</th>
                    <th style="width: 5%">Uom</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outbound->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->goods->name }}</td>
                        <td>P:{{ $item->goods->length }}cm x L:{{ $item->goods->width }}cm x T:{{ $item->goods->height }}cm, B:{{ $item->goods->weight }}kg</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->goods->unit->symbol }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: center">Total</th>
                    <td>{{ $outbound->items->sum('qty') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        <table class="footer">
            <tr>
                <td style=" text-align: center; width: 33.33%"><b>Pengirim</b></td>
                <td style=" text-align: center; width: 33.33%"><b>Pembawa</b></td>
                <td style=" text-align: center; width: 33.33%"><b>Penerima</b></td>
            </tr>
            <tr>
                <td style=" text-align: center;"></td>
                <td style=" text-align: center;"></td>
                <td style=" text-align: center;">
                    @if ($outbound->status == 'Success')
                    <img src="{{ $outbound->project->user->signature->getSignatureImageAbsolutePath() ?? '-' }}" alt="" id="signature">
                    @endif
                </td>
            </tr>
            <tr>
                <td style=" text-align: center;">{{ $outbound->user->name }}</td>
                <td style=" text-align: center;">{{ $outbound->sender_name }}</td>
                <td style=" text-align: center;">{{ $outbound->project->name }}</td>
            </tr>
        </table>
    </div>
</body>

</html>

