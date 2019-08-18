<?php

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            'France' => 'FR',
            'Italy' => 'IT',
            'Swizerland' => 'CH',
            'United Kingdom' => 'UK',
            'United States of America' => 'US'
        ];

        collect($countries)->each(function ($code, $name) {
            Country::create([
                'code' => $code,
                'name' => $name
            ]);
        });
    }
}
