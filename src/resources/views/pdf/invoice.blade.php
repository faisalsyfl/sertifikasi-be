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

.biaya {
    text-align: right;
}

.border-right {
    border-right: 1px solid #000;
}
</style>

<table class="data-table" width="100%">
    <tr>
        <th colspan="5" class="border-top border-bottom border-left border-right">INVOICE</th>
    </tr>
    <tr>
        <td colspan="2" class="border-bottom border-left border-right"><br />Tanggal :{{$tanggal}}<br />No :
            1<br />No Order: {{$nomor_order}}</td>
        <td colspan="3" class="border-bottom border-right">Peminta Jasa : {{$peminta_jasa}}</td>
    </tr>
    <tr>
        <td colspan="5" class="border-bottom border-left border-right"></td>
    </tr>
    <tr>
        <td class="border-bottom border-left border-right">No.</td>
        <td class="border-bottom border-right">Uraian</td>
        <td class="border-bottom border-right">Jumlah</td>
        <td class="border-bottom border-right">Harga Satuan</td>
        <td class="border-bottom border-right">Jumlah</td>
    </tr>
    @php
    $total = 0;
    @endphp

    @for ($i = 0; $i < count($data); $i++) <tr>
        {{$subtotal = $data[$i]['satuan'] * $data[$i]['jumlah']}}
        <td class="border-bottom border-left border-right">{{$i+1}}.</td>
        <td class="border-bottom border-right">{{$data[$i]['uraian']}}</td>
        <td class="border-bottom border-right">{{$data[$i]['jumlah']}}</td>
        <td class="border-bottom border-right biaya">Rp. {{number_format($data[$i]['satuan'], 0, ',', '.')}}</td>
        <td class="border-bottom border-right biaya">Rp. {{number_format($subtotal, 0, ',', '.')}}</td>
        {{ $total+=$subtotal}}
        </tr>
        @endfor
        <tr>
            <td colspan="4" class="border-bottom border-left border-right">Total</td>
            <td class="border-bottom border-right biaya">Rp. {{number_format($total, 0, ',', '.')}}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom border-left border-right">
                Terbilang:&nbsp;<br /><br />{{terbilang($total)}}
        </tr>
        <tr>
            <td colspan="5" class="border-bottom border-left border-right">Virtual Account No: {{$va_number}}<br />Bank :
                BNI</td>
        </tr>
</table>
@include('pdf/footer')
