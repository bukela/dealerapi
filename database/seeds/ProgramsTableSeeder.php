<?php

use Illuminate\Database\Seeder;

class ProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $program = [
            '192' => 'CFC Auto Program 00',
            '194' => 'CFC Auto Program 01',
            '195' => 'CFC Auto Program 02',
            '196' => 'CFC Auto Program 03',
            '197' => 'CFC Auto Program 04',
            '198' => 'CFC Auto Program 05',
            '199' => 'CFC Auto Program 06',
            '200' => 'CFC Auto Program 07',
            '201' => 'CFC Auto Program 08',
            '202' => 'CFC Auto Program 09',
            '203' => 'CFC Auto Program 10',
            '204' => 'CFC Eagle HI Program',
            '213' => 'CFC HA Program',
            '206' => 'CFC HI Program',
            '215' => 'CFC HI Summer Promo Program',
            '210' => 'CFC Hot Tub Program',
            '182' => 'CFC Powersports Program 01 $2,500 - $2,999',
            '183' => 'CFC Powersports Program 02 $3,000 - $9,999',
            '184' => 'CFC Powersports Program 03 $10,000 - $14,999',
            '185' => 'CFC Powersports Program 04 $15,000 - $22,500',
            '187' => 'CFC Powersports Program 05 $15,000 - $22,500 (Amortization)'
        ];

        foreach ($program as $pro => $name)
            {
                App\Program::create(['credit_program_id' => $pro, 'name' => $name]);
            }
    }
}
