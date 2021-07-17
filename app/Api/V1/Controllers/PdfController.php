<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class PdfController extends Controller
{
    public function invoice(Request $request)
    {
        $data_invoice = [
            [
                "uraian" => "Biaya Uji Profisiensi Ban dengan parameter Dimensi",
                "satuan" => 342123,
                "jumlah" => 1,
            ], [
                "uraian" => "Biaya Uji Profisiensi Ban dengan parameter Dimensi",
                "satuan" => 121411,
                "jumlah" => 2,
            ], [
                "uraian" => "Biaya Uji Profisiensi Ban dengan parameter Dimensi",
                "satuan" => 6235623,
                "jumlah" => 2,
            ],
        ];

        $data = [
            'nomor' => '426/PUP/BBBBT- Keu/VII/2021',
            'nomor_order' => '426',
            'nomor_ref' => '-',
            'peminta' => 'SUCOFINDO Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'data' => $data_invoice
        ];
        $pdf = PDF::loadView('pdf/invoice', $data);
        return $pdf->download('b4t-invoice-' . date("Y/m/d") . '.pdf');
    }

    public function penawaran(Request $request)
    {
        $data_invoice = [
            [
                "uraian" => "Semen",
                "satuan" => 35233,
                "jumlah" => 10,
            ], [
                "uraian" => "Batako",
                "satuan" => 2123,
                "jumlah" => 27,
            ], [
                "uraian" => "kusen",
                "satuan" => 323400,
                "jumlah" => 2,
            ],
        ];
        $data = [
            'kepada' => 'PT. Telkom',
            'alamat' => 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'data' => $data_invoice
        ];
        $pdf = PDF::loadView('pdf/penawaran', $data);
        return $pdf->download('b4t-penawaran-' . date("Y/m/d") . '.pdf');
    }

    public function kwitansi(Request $request)
    {
        $data = [
            'kepada' => 'PT. Telkom',
            'alamat' => 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id'
        ];
        $pdf = PDF::loadView('pdf/kwitansi', $data);
        return $pdf->download('b4t-kwitansi-' . date("Y/m/d") . '.pdf');
    }
}