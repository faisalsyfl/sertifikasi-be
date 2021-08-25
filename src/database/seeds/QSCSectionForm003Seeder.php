<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QSCSectionForm003Seeder extends Seeder
{
    public $tableName = 'section_form';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section_forms = [
            [
                'section_id' => 1,
                'key' => 'send_to_email',
                'rule' => null,
            ]
        ];
        foreach ($section_forms as $section_form) {
            $record = \App\Models\SectionForm::where("section_id", $section_form['section_id'])
                ->where("key", $section_form['key'])->first();
            if (!$record) {
                DB::table($this->tableName)->insert([
                    [
                        'section_id' => $section_form['section_id'],
                        'key' => $section_form['key'],
                        'rule' => $section_form['rule'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]
                ]);
            } else {
                $record->update([
                    'rule' => $section_form['rule'],
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
