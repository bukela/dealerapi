<?php

use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $province = array(
            "AB" => "Alberta",
            "BC" => "British Columbia",
            "MB" => "Manitoba",
            "NB" => "New Brunswick",
            "NL" => "Newfoundland and Labrador",
            "NS" => "Nova Scotia",
            "ON" => "Ontario",
            "PE" => "Prince Edward Island",
            "QC" => "Quebec",
            "SK" => "Saskatchewan",
            "NT" => "Northwest Territories",
            "NU" => "Nunavut",
            "YT" => "Yukon"
        );

        foreach ($province as $abbr => $name)
            {
                App\Province::create(['abbr' => $abbr, 'name' => $name]);
            }
    }
}
