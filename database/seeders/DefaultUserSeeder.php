<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create([
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@cloudstaff.com'
        ]);
    }
}
