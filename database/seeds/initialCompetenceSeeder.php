<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class initialCompetenceSeeder extends Seeder
{
    public $tableName = 'competence';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->truncate();

        $sistems = [
          'Mutu',
          'Lingkungan',
          'Produk',
          'Keselamatan',
          'Industri Hijau'
        ];

        $sektors = [
          ['1', 'Agriculture, forestry and fishing'],
          ['2', 'Mining and quarrying'],
          ['3', 'Food products, beverages and tobacco'],
          ['4', 'Textiles and textile products'],
          ['5', 'Leather and leather products'],
          ['6', 'Wood and wood products'],
          ['7', 'Pulp, paper and paper products'],
          ['8', 'Publishing companies'],
          ['9', 'Printing companies'],
          ['10', 'Manufacture of coke and refined petroleum products'],
          ['11', 'Nuclear fuel'],
          ['12', 'Chemicals, chemical products and fibres'],
          ['13', 'Pharmaceuticals'],
          ['14', 'Rubber and plastic products'],
          ['15', 'Nonmetallic mineral products'],
          ['16', 'Concrete, cement, lime, plaster etc'],
          ['17', 'Basic metals and fabricated metal products'],
          ['18', 'Machinery and equipment'],
          ['19', 'Electrical and optical equipment'],
          ['20', 'Shipbuilding'],
          ['21', 'Aerospace'],
          ['22', 'Other transport equipment'],
          ['23', 'Manufacturing not elsewhere classified'],
          ['24', 'Recycling'],
          ['25', 'Electricity supply'],
          ['26', 'Gas supply'],
          ['27', 'Water supply'],
          ['28', 'Construction'],
          ['29', 'Wholesale and retail trade; repair of motor vehicles, motorcycles and personal and household goods'],
          ['30', 'Hotels and restaurants'],
          ['31', 'Transport, storage and communication'],
          ['32', 'Financial intermediation; real estate; renting'],
          ['33', 'Information technology'],
          ['34', 'Engineering services'],
          ['35', 'Other services'],
          ['36', 'Public administration'],
          ['37', 'Education'],
          ['38', 'Health and social work'],
          ['39', 'Other social services'],
        ];

        $nace = [
            ['1','1','Crop and animal production, hunting and related service activities'],
            ['1','2','Forestry and logging'],
            ['1','3','Fishing and aquaculture'],
            ['2','5','Mining of coal and lignite'],
            ['2','6','Extraction of crude petroleum and natural gas'],
            ['2','7','Mining of metal ores'],
            ['2','8','Other mining and quarrying'],
            ['2','9','Mining support service activities'],
            ['3','10','Manufacture of food products'],
            ['3','11','Manufacture of beverages'],
            ['3','12','Manufacture of tobacco products'],
            ['4','13','Manufacture of textiles'],
            ['4','14','Manufacture of wearing apparel'],
            ['5','15','Manufacture of leather and related products'],
            ['6','16','Manufacture of wood and of products of wood and cork, except furniture; manu- facture of articles of straw and plaiting materials'],
            ['7','17','Manufacture of paper and paper products'],
            ['8','58.1','Publishing of books, periodicals and other publishing activities'],
            ['8','59.2','Sound recording and music publishing activities'],
            ['9','18','Printing and reproduction of recorded media'],
            ['10','19','Manufacture of coke and refined petroleum products'],
            ['11','24.46','Processing of nuclear fuel'],
            ['12','20','Manufacture of chemicals and chemical products'],
            ['13','21','Manufacture of basic pharmaceutical products and pharmaceutical preparations'],
            ['14','22','Manufacture of rubber and plastic products'],
            ['15','23','Manufacture of other non-metallic mineral products except 23.5 Manufacture of cement, lime and plaster and 23.6 Manufacture of articles of concrete, cement and plaster'],
            ['16','23.5','Manufacture of cement, lime and plaster'],
            ['16','23.6','Manufacture of articles of concrete, cement and plaster'],
            ['17','24','Manufacture of basic metals except 24.46 Processing of nuclear fuel'],
            ['17','25','Manufacture of fabricated metal products, except machinery and equipment except 25.4 Manufacture of weapons and ammunition and 33.11 Repair of fabircated metal products'],
            ['18','25.4','Manufacture of weapons and ammunition'],
            ['18','28','Manufacture of machineryand equipment n.e.c.'],
            ['18','30.4','Manufacture of military fighting vehicles'],
            ['18','33.12','Repair of machinery'],
            ['18','33.2','Installation of industrial machinery and equipment'],
            ['19','26','Manufacture of computer, electronic and optical products'],
            ['19','27','Manufacture of electrical equipment'],
            ['19','33.13','Repair of electronic and optical equipment'],
            ['19','33.14','Repair of electrical equipment'],
            ['19','95.1','Repair of computers and personal and household goods'],
            ['20','30.1','Building of ships and boats'],
            ['20','33.15','Repair and maintenance of ships and boats'],
            ['21','30.3','Manufacture of air and spacecraft and related machinery'],
            ['21','33.16','Repair and maintenance of aircraft and spacecraft'],
            ['22','29','Manufacture of motor vehicles, trailers and semi-trailers'],
            ['22','30.2','Manufacture of railway locomotives and rolling stock'],
            ['22','30.9','Manufacture of transport equipment n.e.c'],
            ['22','33.17','Repair and maintenance of other transport equipment'],
            ['23','31','Manufacture of furniture'],
            ['23','32','Other manufacturing'],
            ['23','33.19','Repair of other equipment'],
            ['24','38.3','Materials recovery'],
            ['25','35.1','Electrical power generation, transmission and distribution'],
            ['26','35.2','Manufacture of gas; distribution of gaseous fuels through mains'],
            ['27','35.3','Steam and air conditioning supply'],
            ['27','36','Water collection, treatment and supply'],
            ['28','41','Construction of buildings'],
            ['28','42','Civil engineering'],
            ['28','43','Specialised construction activities'],
            ['29','45','Wholesale and retail trade and repair of motor vehicles and motorcycles'],
            ['29','46','Wholesale trade, except of motor vehicles and motorcycles'],
            ['29','47','Retail trade, except of motor vehicles and motorcycles'],
            ['29','95.2','Repair of personal and household goods'],
            ['30','55','Accommodation'],
            ['30','56','Food and beverage service activities'],
            ['31','49','Land transport and transport via pipelines'],
            ['31','50','Water transport'],
            ['31','51','Air transport'],
            ['31','52','Warehousing and support activities for transportation'],
            ['31','53','Postal and courier activities'],
            ['31','61','Telecommunications'],
            ['32','64','Financial service activities, except insurance and pension funding'],
            ['32','65','Insurance, reinsurance and pension funding, except compulsorysocial security'],
            ['32','66','Activities auxiliary to financial services and insurance activities'],
            ['32','68','Real estate activities'],
            ['32','77','Rental and leasing activities'],
            ['33','58.2','Software publishing'],
            ['33','62','Computer programming, consultancy and related activities'],
            ['33','63.1','Data processing, hosting and related activities; web portals'],
            ['34','71','Architectural and engineering activities; technical testing and analysis'],
            ['34','72','Scientific research and development'],
            ['34','74','Other professional, scientific and technical activities except 74.2 Photographic activities and 74.3 Translation and iterpretation activities'],
            ['35','69','Legal and accounting activities'],
            ['35','70','Activities of head offices; management consultancy activities'],
            ['35','73','Advertising and market research'],
            ['35','74.2','Photographic activities'],
            ['35','74.3','Translation and interpretation activities'],
            ['35','78','Employment activities'],
            ['35','80','Security and investigation activities'],
            ['35','81','Services to buildings and landscape activities'],
            ['35','82','Office administrative, office support and other business support activities'],
            ['36','84','Public administration and defence; compulsory social security'],
            ['37','85','Education'],
            ['38','75','Veterinary activities'],
            ['38','86','Human health activities'],
            ['38','87','Residential care activities'],
            ['38','88','Social work activities without accommodation'],
            ['39','37','Sewerage'],
            ['39','38.1','Waste collection'],
            ['39','38.2','Waste treatment and disposal'],
            ['39','39','Remediation activities and other waste management services'],
            ['39','59.1','Motion picture, video and television programme activities'],
            ['39','60','Programming and broadcasting activities'],
            ['39','63.9','Other information service activities'],
            ['39','79','Travel agency, tour operator reservation service and related activities'],
            ['39','90','Creative, arts and entertainment activities'],
            ['39','91','Libraries, archives, museums and other cultural activities'],
            ['39','92','Gambling and betting activities'],
            ['39','93','Sports activities and amusement and recreation activities'],
            ['39','94','Activities of membership organisations'],
            ['39','96','Other personal service activities'],
        ];

        for($i=0;$i<count($sistems);$i++){
          DB::table($this->tableName)->insert([
              'name' => $sistems[$i],
              'type' => 'Sistem',
              'code' => $sistems[$i] == "Industri Hijau" ? "IH" : substr($sistems[$i],0,1),
              'parent_code' => null,
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]);
        }

        for($i=0;$i<count($sektors);$i++){
          DB::table($this->tableName)->insert([
              'name' => $sektors[$i][1],
              'type' => 'Sektor',
              'code' => $sektors[$i][0],
              'parent_code' => null,
              'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]);
        }

        for($i=0;$i<count($nace);$i++){
            DB::table($this->tableName)->insert([
                'name' => $nace[$i][2],
                'type' => 'NACE',
                'code' => $nace[$i][1],
                'parent_code' => $nace[$i][0],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
