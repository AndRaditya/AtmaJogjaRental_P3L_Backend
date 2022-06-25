<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table,
        th,
        td {
            /* font-size: 9pt;
			border-collapse: collapse;
			border: 1px solid; */
            border-collapse: collapse;
            width: 100%;

        }

        /* th, td{
			border-bottom: 1px solid #ddd;
		} */

    </style>

    <h5 style="text-align: center">Form Penyewaan Mobil</h5>
    <h5 style="text-align: center">Atma Jogja Rental</h5>
    <hr>
    <br>

    <h6>Nota Transaksi Sewa Mobil</h4>
        <span></span>
        <table style="border: 1px solid black">
            <table style="border: 1px solid black">
                <tr>
                    <th colspan="4" style="text-align: center">Atma Rental</th>
                </tr>
                <tr>
                    <td colspan="2">{{ $id_transaksi }}</td>
				    <td colspan="2">{{ $tgl_transaksi }}</td>
                </tr>
            </table>

            <table style="border: 1px solid black">
                <tr>
                    <td colspan="1">Nama Customer: </td>
                    <td colspan="1">{{ $nama_customer }}</td>
                    <td colspan="1">Promo: </td>
                    <td colspan="1">{{ $kode_promo }}</td>
                </tr>
                <tr>
                    <td colspan="1">Nama CS: </td>
                    <td colspan="3"> {{ $nama_pegawai }}</td>
                </tr>
                <tr>
                    <td colspan="1">Nama Driver: </td>
                    <td colspan="3"> {{ $nama_driver }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
            </table>

            <table style="border: 1px solid;">
                <tr>
                    <td colspan="4" style="height: 25px">
                    </td>
                </tr>
            </table>

            <table style="border: 1px solid black">
                <tr>
                    <th colspan="4" style="text-align: center">Nota Transaksi</th>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: center">{{ $id_detail_transaksi }}</td>
                </tr>

                <tr>
                    <td colspan="1">Tanggal Mulai: </td>
                    <td colspan="3">{{ $tgl_mulai }} </td>
                </tr>
                <tr>
                    <td colspan="1">Tanggal Selesai: </td>
                    <td colspan="3">{{ $tgl_selesai }} </td>
                </tr>
                <tr>
                    <td colspan="1">Tanggal Pengembalian: </td>
                    <td colspan="3">{{ $tgl_pengembalian }} </td>
                </tr>

                <tr>
                    <th>Item</th>
                    <th>Satuan</th>
                    <th>Durasi</th>
                    <th>Sub Total</th>
                </tr>
                <tr>
                    <td>{{ $nama_mobil }}</td>
                    <td>{{ $harga_mobil }}</td>
                    <td>{{ $durasi }} hari</td>
                    <td>{{ $total_biaya_mobil }}</td>
                </tr>
                <tr>
                    <td>Driver {{ $nama_driver }}</td>
                    <td>{{ $biaya_driver_satuan }}</td>
                    <td>{{ $durasi }} hari</td>
                    <td>{{ $total_biaya_driver }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $total_driver_mobil }}</td>
                </tr>
            </table>

			<table style="border: 1px solid;">
                <tr>
                    <td colspan="4" style="height: 25px">
                    </td>
                </tr>
            </table>

            <table style="border: 1px solid black">
                <tr>
                    <td>Customer</td>
                    <td>Customer Service</td>
                    <td>Diskon</td>
                    <td>{{ $diskon }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="1">Denda</td>
                    <td colspan="1">{{ $denda }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="1">Total</td>
                    <td colspan="1">{{ $total_biaya }}</td>
                </tr>
                <tr>
                    <td colspan="1">{{ $nama_customer }}</td>
                    <td colspan="1"> {{ $nama_pegawai }}</td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </table>
</body>

</html>
