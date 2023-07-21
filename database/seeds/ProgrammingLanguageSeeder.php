<?php

use Illuminate\Database\Seeder;
use App\Models\ProgrammingLanguage;

class ProgrammingLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programmingLanguages = [
            ['name' => 'C++'],
            ['name' => 'Java'],
            ['name' => 'PHP'],
            ['name' => 'React'],
            ['name' => 'Android'],
            ['name' => 'Laravel'],
        ];

        $now = now();

        $programmingLanguages = array_map(function($programmingLanguage) use ($now) {
            return array_merge($programmingLanguage, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $programmingLanguages);

        ProgrammingLanguage::insert($programmingLanguages);
    }
}
