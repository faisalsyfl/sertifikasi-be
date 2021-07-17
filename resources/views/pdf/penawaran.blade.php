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
</style>

<p align="left">
    Kepada Yth.<br>
    {{$kepada}}<br>
    {{$alamat}}<br><br>

    Sehubungan dengan permintaan saudara melalui layanan booking online kami. Dengan ini kami sampaikan informasi biaya
    untuk pelaksanaan Pengujian Bahan adalah sebagai berikut :
</p>

<table class="data-table" width="100%">
    <tr>
        <th class="border-top border-bottom border-left border-right">No</th>
        <th class="border-top border-bottom border-right">Uraian</th>
        <th class="border-top border-bottom border-right">Satuan</th>
        <th class="border-top border-bottom border-right">Jumlah</th>
        <th class="border-top border-bottom border-right">Total</th>
    </tr>

    @php
    $total = 0;
    @endphp

    @for ($i = 0; $i < count($data); $i++) <tr>
        {{$subtotal = $data[$i]['satuan'] * $data[$i]['jumlah']}}
        <td class="border-bottom border-left border-right">{{$i+1}}.</td>
        <td class="border-bottom border-right">{{$data[$i]['uraian']}}</td>
        <td class="border-bottom border-right">{{$data[$i]['satuan']}}</td>
        <td class="border-bottom border-right">{{$data[$i]['jumlah']}}</td>
        <td class="border-bottom border-right">{{$subtotal}}</td>
        {{ $total+=$subtotal}}
        </tr>
        @endfor
        <tr>
            <td colspan="4" class="border-bottom border-left border-right">Item Total</td>
            <td class="border-bottom border-right">{{$total}}</td>
        </tr>
        <tr>
            <td colspan="4" class="border-bottom border-left border-right">{{terbilang($total)}}</td>
            <td class="border-bottom border-right"></td>
        </tr>
</table>

<p align="left">
    Pembayaran dapat dilakukan melalui Virtual Account Bank BNI dengan Nomor: 9883333706013595. Pengujian akan
    dilaksanakan setelah pembayaran kami terima.
    Demikian informasi biaya Layanan Jasa kami sampaikan. Atas perhatian dan kerjasama yang baik kami ucapkan terima
    kasih.
</p>

@include('pdf/footer')