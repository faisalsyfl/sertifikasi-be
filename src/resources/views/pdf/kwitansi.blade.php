@include('pdf/header')
<table border="0" style="width: 530; border-collapse: collapse;">
    <tbody>
        <tr style="height: 41px;">
            <td style="text-align: center; vertical-align: middle; width: 609px; height: 41px;" colspan="3">
                <strong><u>KUITANSI</u></strong><br /><strong></strong>
            </td>
        </tr>
        <tr style="height: 41px;">
            <td style="width: 209px; height: 41px;"><span style="text-decoration: underline;">No Rekening VA</span>
                <br />VA Account No
            </td>
            <td style="width: 400px; height: 41px;" colspan="2">{{$va_number}}</td>
        </tr>
        <tr style="height: 41px;">
            <td style="width: 209px; height: 41px;"><span style="text-decoration: underline;">Sudah terima
                    dari</span> <br />Received from</td>
            <td style="width: 400px; height: 41px;" colspan="2">{{$nama}}</td>
        </tr>
        <tr style="height: 41px;">
            <td style="width: 209px; height: 41px;"><span style="text-decoration: underline;">Keterangan</span>
                <br />Explanation
            </td>
            <td style="width: 400px; height: 41px;" colspan="2">{{$desc}}</td>
        </tr>
        <tr style="height: 41px;">
            <td style="width: 209px; height: 41px;"><span style="text-decoration: underline;">Uang
                    Sejumlah</span><br />A sum of</td>
            <td class="kotakna" colspan="2">&nbsp;{{ strtoupper(terbilang($nilai)) }}</td>
        </tr>
        <tr style="height: 41px;">
            <td style="width: 209px; height: 41px;"><span style="text-decoration: underline;">Untuk
                    Pembayaran</span><br />Content</td>
            <td style="width: 400px; height: 41px;" colspan="2">Biaya Sertifikasi {{ $nomor_registrasi }}</td>
        </tr>
        <tr style="height: 21px;">
            <td style="width: 209px; height: 21px;"></td>
            <td style="width: 196px; height: 41px; border: 1px solid black; text-align: center;">Rp. {{number_format($nilai, 0, ',', '.')}}</td>
            <td>
        </tr>
        <tr style="height: 61px;">
            <td style="width: 209px; height: 61px;"></td>
            <td style="width: 196px; height: 61px;"></td>
            <td style="width: 204px; text-align: right; height: 61px;">{{$tempat_tanggal}}<br />Bendaharawan<br />
                <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{ $pdf_file }}&choe=UTF-8" title="Link to Google.com" />
            </td>
        </tr>
    </tbody>
</table>
@include('pdf/footer')
