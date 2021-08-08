@include('pdf/header')
<style>
.data-table {
    border-collapse: collapse;
}

.border-top {
    border-top: 1px solid #000;
}

.border-bottom {
    border-bottom: 1px solid #000;
}

.border-left {
    border-left: 1px solid #000;
}

.border-right {
    border-right: 1px solid #000;
}

.nomor {
    width: 30px;
}
.biaya {
    text-align: right;
}

.catatan {
    text-align: left;
}
</style>

<table width="100%">
    <tr>
        <td style="width: 10%">Nomor</td>
        <td style="width: 1%">:</td>
        <td style="width: 59%">{{$nomor_dokumen}}</td>
        <td style="width: 30%">{{$tempat_tanggal}}</td>
    </tr>
    <tr>
        <td>Lampiran</td>
        <td>:</td>
        <td>-</td>
        <td></td>
    </tr>
    <tr>
        <td>Perihal</td>
        <td>:</td>
        <td><b><u>Biaya {{$sertifikasi." ". $jenis}} </u></b></td>
        <td></td>
    </tr>
</table>

<p align="left">
    Yth.<br>
    {{$nama}}<br>
    {{$alamat}}<br><br>

    Sehubungan dengan permohonan {{$sertifikasi." Sistem ".$jenis_lengkap}} dari {{ $nama }}, dengan ini kami sampaikan biaya
    sertifikasi sesuai Tarif Badan Layanan Umum (BLU) B4T dengan sistem paket sebagai berikut:
</p>

<table class="data-table" width="100%">
    <tr>
        <th class="border-top border-bottom border-left border-right nomor">No</th>
        <th class="border-top border-bottom border-right">Kegiatan</th>
        <th class="border-top border-bottom border-right">Biaya</th>
    </tr>

    @php
    $total = 0;
    @endphp

    @for ($i = 0; $i < count($data); $i++) <tr>
        <td class="border-bottom border-left border-right">{{$i+1}}.</td>
        <td class="border-bottom border-right">{{$data[$i]['kegiatan']}}</td>
        <td class="border-bottom border-right biaya">Rp. {{number_format($data[$i]['biaya'], 0, ',', '.')}}</td>
        {{ $total+=$data[$i]['biaya']}}
        </tr>
        @endfor
        <tr>
            <td colspan="2" class="border-bottom border-left border-right"><b>Total</b></td>
            <td class="border-bottom border-right biaya"><b>Rp. {{number_format($total, 0, ',', '.')}}</b></td>
        </tr>
        <tr>
            <td colspan="3" class="border-bottom border-left border-right"><b>Terbilang: {{terbilang($total)}}</b></td>
        </tr>
</table>

<p style="text-align: left">
    Catatan:<br>
    <ol class="catatan">
        <li>Biaya tersebut diluar biaya akomodasi {{ $transport_0 }}</li>
        <li>Biaya tersebut tidak dikenakan pajak, karena B4T merupakan instansi pemerintah yang tidak menarik PPH dan PPN</li>
        <li><b>Pembayaran dilakukan dengan transfer melalui pembayaran Virtual Account BIN, dengan nomor {{ $va_number }}
            sebelum tanggal {{ $va_expire }}</b></li>
        <li>Proses Audit/Sertifikasi dapat dilakukan setelah bukti pembayaran diterima oleh Balai Besar Bahan dan Barang Teknik</li>
    </ol>

    <br>
    <p style="text-align: left">
        Atas perhatian dan kerjasamanya kami ucapkan terimakasih.
    </p>
    <br>

    <p align="right">
        Kepala Balai Besar Bahan dan Barang Teknik<br>
        <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{ $pdf_file }}&choe=UTF-8" title="Link to Google.com" />
    </p>

    <p style="text-align: left">
        Tembusan:
        <ol class="catatan">
            <li>1. Plt Ka. Bid Sertifikasi</li>
        </ol>
    </p>
</p>

@include('pdf/footer')
