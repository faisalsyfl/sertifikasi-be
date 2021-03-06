<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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

    static function generateInvoice($data, $target="download"){
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
        $tanggal_invoice = isset($data['tanggal_invoice']) ? $data['tanggal_invoice'] : date("Y-m-d H:i:s");
        $month =  (int)date("m", strtotime($tanggal_invoice));
        $month_roman = self::numberToRomanRepresentation($month);
        $year = date("Y", strtotime($tanggal_invoice));
        $file_path = 'public/pdf/'.$id.'_invoice_'.date("YmdHis").'.pdf';

        $info = [
            'peminta_jasa' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'kepada' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'alamat' => isset($data['alamat_klien']) ? $data['alamat_klien'] : 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'tanggal' => date("Y/m/d", strtotime($tanggal_invoice)),
            'nomor' => $id.'/PUP/BBBBT- Keu/'.$month_roman.'/'.$year,
            'nomor_order' => $id,
            'nomor_ref' => '-',
            'peminta' => 'SUCOFINDO Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'va_number' => isset($data['va_number']) ? $data['va_number'] : '1234567890',
            'data' => $data_invoice
        ];
        $pdf = PDF::loadView('pdf/invoice', $info);

        if($target == "file_path"){
            $file = $pdf->output();
            Storage::disk('local')->put($file_path,$file);

            return str_replace("public", "storage",$file_path);
        }else{
            return $pdf->download('b4t-invoice-' . date("Y/m/d") . '.pdf');
        }
    }

    static function generatePenawaran($data, $target="download"){
        $data_invoice = [
            [
                "kegiatan" => "Biaya Sertifikasi",
                "biaya" => isset($data['biaya_sertifikasi']) ? $data['biaya_sertifikasi'] : 1000000
            ],
            [
                "kegiatan" => "Transportasi",
                "biaya" => isset($data['transportasi']) ? $data['transportasi'] : 0
            ],
        ];

        $id = isset($data['transaction_id']) ? $data['transaction_id'] : '1';
        $tanggal_invoice = isset($data['tanggal_invoice']) ? $data['tanggal_invoice'] : date("Y-m-d H:i:s",time());
        $tanggal_expire = isset($data['tanggal_expire']) ? $data['tanggal_expire'] : date("Y-m-d H:i:s",time());
        $month = (int)date("m", strtotime($tanggal_invoice));
        $month_roman = self::numberToRomanRepresentation($month);
        $year = date("Y", strtotime($tanggal_invoice));
        $file_path = 'public/pdf/'.$id.'_penawaran_'.date("YmdHis").'.pdf';
        $public_code = isset($data['public_code']) ? $data['public_code'] : 'invalid';
        $qr_url = env('FRONTEND_URL', 'https://sifion.b4t.go.id/').'pelanggan/'.$public_code;

        # $data['jenis'] = Qsc4::getJenisSertifikasiManajemen(1);
        # $data['jenis_lengkap'] = Qsc4::getJenisSertifikasiManajemen(1, true);
        # $data['sertifikasi'] = Qsc4::getJenisSertifikasi(1);
        # $data['alamat_klien'] = isset($data['alamat_klien']) ? $data['alamat_klien'] : "Jl. Jend. Ahmad Yani No. 106".", ".Qsc4::getFullAddress(1);

        $info = [
            'nomor_dokumen' => 'B/'.str_pad($id,3,"0",STR_PAD_LEFT).'/BSKJI/B4T/MS/'.$month_roman.'/'.$year,
            'tempat_tanggal' => 'Bandung, '.strftime("%d %B %Y", strtotime($tanggal_invoice)),
            'nama' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'alamat' => isset($data['alamat_klien']) ? $data['alamat_klien'] : "Jl. Jend. Ahmad Yani No. 106, Bandung, Jawa Barat, 12345",
            'sertifikasi' => isset($data['sertifikasi']) ? $data['sertifikasi'] : 'Sertifikasi Awal',
            'jenis_lengkap' => isset($data['jenis_lengkap']) ? $data['jenis_lengkap'] : 'Manajemen Mutu ISO 9001:2015',
            'jenis' => isset($data['jenis']) ? $data['jenis'] : 'ISO 9001:2015',
            'va_number' => isset($data['va_number']) ? $data['va_number'] : '0123456789',
            'va_expire' => strftime("%d %B %Y", strtotime($tanggal_expire)),
            'pdf_file' => $file_path,
            'qr_url' => $qr_url,
            'data' => $data_invoice,
            'transport_0' => "",
        ];

        if($data_invoice[1]["biaya"] == 0){
            $info["transport_0"] = " dan transportasi";
            unset($info['data'][1]);
        }

        $pdf = PDF::loadView('pdf/penawaran', $info);

        if($target == "file_path"){
            $file = $pdf->output();
            Storage::disk('local')->put($file_path,$file);

            return str_replace("public", "storage",$file_path);
        }else{
            return $pdf->download('b4t-penawaran-'.$id."-".date("Y/m/d") . '.pdf');
        }
    }

    static function generateKwitansi($data, $target="download"){
        $tanggal_invoice = isset($data['tanggal_invoice']) ? $data['tanggal_invoice'] : date("Y-m-d H:i:s",time());
        $transaction_id = isset($data["transaction_id"]) ? $data["transaction_id"] : 1;
        $file_path = 'public/pdf/'.$transaction_id.'_kuitansi_'.date("YmdHis").'.pdf';
        $public_code = isset($data['public_code']) ? $data['public_code'] : 'invalid';
        $qr_url = env('FRONTEND_URL', 'https://sifion.b4t.go.id/').'pelanggan/'.$public_code;

        # $data['jenis'] = Qsc4::getJenisSertifikasiManajemen(1);
        # $data['sertifikasi'] = Qsc4::getJenisSertifikasi(1);

        $info = [
            'va_number' => isset($data['va_number']) ? $data['va_number'] : '0123456789',
            'nama' => isset($data['nama_klien']) ? $data['nama_klien'] : 'PT. Telkom',
            'alamat' => isset($data['alamat_klien']) ? $data['alamat_klien'] : 'Jl. Jend. Ahmad Yani no 106 Cilegon 42421, Banten tarkun@sucofindo.co.id',
            'desc' => '-',
            'nilai' => isset($data['total']) ? $data['total'] : 100000,
            'nomor_registrasi' => $transaction_id,
            'tempat_tanggal' => 'Bandung, '.strftime("%d %B %Y", strtotime($tanggal_invoice)),
            'pdf_file' => $file_path,
            'sertifikasi' => isset($data['sertifikasi']) ? $data['sertifikasi'] : 'Sertifikasi Awal',
            'jenis' => isset($data['jenis']) ? $data['jenis'] : 'ISO 9001:2015',
        ];

        $pdf = PDF::loadView('pdf/kwitansi', $info);

        if($target == "file_path"){
            $file = $pdf->output();
            Storage::disk('local')->put($file_path,$file);

            return str_replace("public", "storage",$file_path);
        }else{
            return $pdf->download('b4t-kwitansi-'.$transaction_id."-". date("Y/m/d") . '.pdf');
        }
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
