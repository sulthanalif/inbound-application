<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note</title>
    <style>
        .content {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        .header {
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }
        .header img {
            display: block;
            margin-left: 0;
        }
        .header p {
            text-align: left;
        }
        .footer td {
            border: none;

        }
        .footer tr:first-child {
            height: 100px;
        }

        #title {
            font-size: 1.5em;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        #keterangan-kiri, #keterangan-kanan {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        #keterangan-kiri td, #keterangan-kanan td {
            vertical-align: top;
            padding: 5px;
        }
        #table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        #table th, #table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        #table th {
            background-color: #f2f2f2;
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
                <td style="text-align: left;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.jpg'))) }}" width="150" height="50" alt="logo"><br>
                    <p>Stockyard KWJJV Package 2 & 3<br>
                        Jl. sunter permai raya depan perumahan t.s.a 2 tanah kosong, RT.2/RW.12, Sunter Agung, Kec. Tj. Priok,
                        Jkt Utara. Daerah Khusus Ibukota Jakarta 14350</p>
                </td>
            </tr>
        </table>
        <div style="display: flex; align-items: center; justify-content: center; margin-top: 20px; margin-bottom: 20px">

                    <b style="font-size: 25px"><u>Surat Jalan</u></b>

        </div>
        <table>

            <tr id="keterangan-kiri" style="font-size: 14px">
                <td style="width: 30%">
                    <b>No Kendaraan</b><br>
                    <b>Nama Pengendara</b><br>
                    <b>Penerima</b><br>
                    <b>Alamat</b><br>
                    <b>Kontak</b><br>
                    <b>Keterangan</b>
                </td>
                <td>
                    <b>: {{ $inbound->vehicle_number }}</b><br>
                    <b>: {{ $inbound->sender_name }}</b><br>
                    <b>: {{ $inbound->user->name }} ({{ $inbound->user->company ?? '-' }})</b><br>
                    <b>: {{ $inbound->project->address }}</b><br>
                    <b>: {{ $inbound->user->phone ?? '-' }}</b><br>
                    <b>: {{ $inbound->description ?? '-' }}</b>
                </td>
                <td style="width: 20%">
                    <b>No</b><br>
                    <b>Tanggal</b><br>
                </td>
                <td>
                    <b>: {{ $inbound->number }}</b><br>
                    <b>: {{ \Carbon\Carbon::parse($inbound->date)->format('d F Y') }}</b><br>
                </td>
            </tr>

        </table>
        <table id="table">
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
                @foreach ($inbound->items as $item)
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
                    <td>{{ $inbound->items->sum('qty') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <table class="footer">
            <tr>
                <td colspan="3">
                    <p>Barang-barang di atas tersebut dari <b>{{ $inbound->user->name }}</b> dan telah diterimah oleh <b>{{ $inbound->project->name }}</b> pada tanggal ...........</p>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; width: 33.33%"><b>Pengirim</b></td>
                <td style="text-align: center; width: 33.33%"><b>Pembawa</b></td>
                <td style="text-align: center; width: 33.33%"><b>Penerima</b></td>
            </tr>
            <tr>
                <td style="text-align: center;">@if ($inbound->status == 'Success')
                    <img src="{{ $inbound->project->user->signature->getSignatureImageAbsolutePath() ?? '-' }}" alt="" id="signature">
                    @endif</td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">{{ $inbound->user->name }}</td>
                <td style="text-align: center;">{{ $inbound->sender_name }}</td>
                <td style="text-align: center;">{{ $inbound->project->name }}</td>
            </tr>
        </table>
    </div>
</body>

</html>


