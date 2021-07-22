<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompetenceRiskSeederID extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = [
            [12,'Kimia, produk kimia dan serat'],
            [14,'Karet dan produk plastik'],
            [16,'Beton, semen, kapur, gips dll'],
            [17,'Logam dasar dan produk terbuat dari logam'],
            [18,'Mesin dan peralatan'],
            [19,'Peralatan listrik dan peralatan optik'],
            [22,'Peralatan transportasi lain'],
            [28,'Konstruksi'],
            [29,'Perdagangan grosir dan eceran, reparasi kendaraan bermotor dan barang keperluan rumah tangga'],
            [33,'Teknologi informasi'],
            [34,'Jasa engineering'],
            [35,'Jasa lain'],
            [36,'Administrasi umum'],
        ];

        foreach ($sectors as $sector){
            \App\Models\Competence::where("Type","Sektor")
                ->where("code", $sector[0])
                ->update([
                    "name_alt" => $sector[1]
                ]);
        }
    }
}
