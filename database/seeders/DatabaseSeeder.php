<?php

namespace Database\Seeders;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CountrySeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(CountrySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SuperAdminSeeder::class);

        $reserv = ReservationFactory::new()->create();
        $reserv->save(); //TODO
    }
}
