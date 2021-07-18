<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;


class PdfController extends Controller
{
    public function invoice(Request $request)
    {
        return self::generateInvoice($request->all());
    }

    public function penawaran(Request $request)
    {
        return self::generatePenawaran($request->all());
    }

    public function kwitansi(Request $request)
    {
        return self::generateKwitansi($request->all());
    }

    static function generateInvoice($data){
        $data_invoice = [
            [
                "uraian" => "Biaya Sertifikasi",
                "satuan" => isset($data['biaya_sertifikasi']) ? $data['biaya_sertifikasi'] : 342123,
                "jumlah" => 1,
            ], [
                "uraian" => "Transportasi",
                "satuan" => isset($data['transportasi']) ? $data['transportasi'] : 121411,
                "jumlah" => 1,
            ]
        ];

        $id = isset($data['transaction_id']) ? $data['transaction_id'] : '426';
        $month =  (int)date("m", isset($data['tanggal_invoice']) ? strtotime($data['tanggal_invoice']) : time());
        $month_roman = self::numberToRomanRepresentation($month);
        $year = date("Y", isset($data['tanggal_invoice']) ? strtotime($data['tanggal_invoice']) : time());

        $info = [
            'kepada' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'alamat' => isset($data['alamat_klien']) ? $data['alamat_klien'] : 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'tanggal' => isset($data['tanggal_invoice']) ? date("Y/m/d", $data['tanggal_invoice']) : date("Y/m/d"),
            'nomor' => $id.'/PUP/BBBBT- Keu/'.$month_roman.'/'.$year,
            'nomor_order' => $id,
            'nomor_ref' => '-',
            'peminta' => 'SUCOFINDO Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'va_number' => isset($data['va_number']) ? $data['va_number'] : '1234567890',
            'data' => $data_invoice
        ];
        $pdf = PDF::loadView('pdf/invoice', $info);
        return $pdf->download('b4t-invoice-' . date("Y/m/d") . '.pdf');
    }

    static function generatePenawaran($data){
        $data_invoice = [
            [
                "kegiatan" => "Biaya Sertifikasi",
                "biaya" => isset($data['biaya_sertifikasi']) ? $data['biaya_sertifikasi'] : 1000000
            ],
            [
                "kegiatan" => "Transportasi",
                "biaya" => isset($data['transportasi']) ? $data['transportasi'] : 100000
            ],
        ];

        $id = isset($data['transaction_id']) ? $data['transaction_id'] : '1';
        $tanggal_invoice = isset($data['tanggal_invoice']) ? $data['tanggal_invoice'] : date("Y-m-d H:i:s",time());
        $tanggal_expire = isset($data['tanggal_expire']) ? $data['tanggal_expire'] : date("Y-m-d H:i:s",time());
        $month =  (int)date("m", strtotime($tanggal_invoice));
        $month_roman = self::numberToRomanRepresentation($month);
        $year = date("Y", strtotime($tanggal_invoice));

        $info = [
            'nomor_dokumen' => 'B/'.$id.'/BSKJI/B4T/MS/'.$month_roman.'/'.$year,
            'tempat_tanggal' => 'Bandung, '.strftime("%d %B %Y", strtotime($tanggal_invoice)),
            'nama' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'alamat' => isset($data['alamat_klien']) ? $data['alamat_klien'] : 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'sertifikasi' => isset($data['sertifikasi']) ? $data['sertifikasi'] : 'Sertifikasi Awal',
            'jenis_lengkap' => isset($data['jenis_lengkap']) ? $data['jenis_lengkap'] : 'Manajemen Mutu ISO 9001:2015',
            'jenis' => isset($data['jenis']) ? $data['jenis'] : 'ISO 9001:2015',
            'va_number' => isset($data['va_number']) ? $data['va_number'] : '0123456789',
            'va_expire' => strftime("%d %B %Y", strtotime($tanggal_expire)),
            'pdf_file' => isset($data['file_url']) ? $data['file_url'] : 'https://api-sertifikasi.b4t.go.id/storage/form_document/form_document_KA4ZWKF7u72021-07-18-10-53-0331.pdf',
            'data' => $data_invoice
        ];
        $pdf = PDF::loadView('pdf/penawaran', $info);
        return $pdf->download('b4t-penawaran-' . date("Y/m/d") . '.pdf');
    }

    static function generateKwitansi($data){
        $tanggal_invoice = isset($data['tanggal_invoice']) ? $data['tanggal_invoice'] : date("Y-m-d H:i:s",time());

        $info = [
            'va_number' => isset($data['va_number']) ? $data['va_number'] : '0123456789',
            'nama' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'alamat' => isset($data['alamat_klien']) ? $data['alamat_klien'] : 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'desc' => '-',
            'nilai' => isset($data['total']) ? $data['total'] : 100000,
            'nomor_registrasi' => isset($data['nomor_registrasi']) ? $data['nomor_registrasi'] : '123456',
            'tempat_tanggal' => 'Bandung, '.strftime("%d %B %Y", strtotime($tanggal_invoice)),
            'pdf_file' => isset($data['file_url']) ? $data['file_url'] : 'https://api-sertifikasi.b4t.go.id/storage/form_document/form_document_KA4ZWKF7u72021-07-18-10-53-0331.pdf',
        ];

        $pdf = PDF::loadView('pdf/kwitansi', $info);
        return $pdf->download('b4t-kwitansi-' . date("Y/m/d") . '.pdf');
    }

    static function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
