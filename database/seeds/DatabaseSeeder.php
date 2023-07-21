<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProgrammingLanguageSeeder::class);
        factory(App\Models\Employee::class, 300)->create();
    }
}