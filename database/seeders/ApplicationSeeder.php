<?php

namespace Database\Seeders;

use Database\Factories\ApplicationFactory;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Application::factory()
            ->count(50)
            ->create();
    }
}
