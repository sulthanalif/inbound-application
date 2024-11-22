<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .content {
            width: 100%;
            margin: 0 auto;
            padding: 10mm;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            vertical-align: top;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="content">
        <table class="header">
            <tr>
                <th colspan="3">
                    <h2 align="center">Delivery Note</h2>
                </th>
            </tr>
            <tr>
                <td rowspan="2">
                    <b>NITTOC STOCKYARD ISS</b><br>
                    Stockyard KWJJV Package 2 & 3<br>
                    Jl. sunter permai raya depan perumahan t.s.a 2 tanah kosong, RT.2/RW.12, Sunter Agung, Kec. Tj. Priok,
                    Jkt
                    Utara. <br> Daerah Khusus Ibukota Jakarta 14350
                </td>
                <th style="text-align: left">No:</th>
                <td>274/DN-35521PK2-PJ-X1-2024</td>
            </tr>
            <tr>
                <th style="text-align: left">Tanggal: </th>
                <td>{{ now()->timezone('Asia/Jakarta')->format('l, d F Y') }}</td>
            </tr>
            <tr>
                <td colspan="3"><b>To Project: </b>{{ $outbound->project->name }}</td>
            </tr>

        </table>
        <table>
            <tr>
                <td style="width: 15%">
                    <b>Penerima</b><br>
                    <b>Alamat</b><br>
                    <b>No Kendaraan</b><br>
                    <b>Nama Pengendara</b><br>
                </td>
                <td>
                    <b>: {{ $outbound->user->name }} ({{ $outbound->user->company ?? '-' }})</b><br>
                    <b>: {{ $outbound->project->address }}</b><br>
                    <b>: {{ $outbound->vehicle_number }}</b><br>
                    <b>: {{ $outbound->sender_name }}</b><br>
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
            <tr style="height: 200px">
                <td style="vertical-align: top; text-align: center; width: 33.33%"><b>Pengirim</b></td>
                <td style="vertical-align: top; text-align: center; width: 33.33%"><b>Pembawa</b></td>
                <td style="vertical-align: top; text-align: center; width: 33.33%"><b>Penerima</b></td>
            </tr>
            <tr>
                <td style="vertical-align: top; text-align: center; width: 33.33%">Stockkeeper</td>
                <td style="vertical-align: top; text-align: center; width: 33.33%">Driver</td>
                <td style="vertical-align: top; text-align: center; width: 33.33%">{{ $outbound->project->name }}</td>
            </tr>
        </table>
    </div>
</body>

</html>

