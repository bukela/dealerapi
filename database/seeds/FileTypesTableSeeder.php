<?php

use Illuminate\Database\Seeder;
use App\FileType;

class FileTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $file_type1 = new FileType;
        $file_type1->code = '1';
        $file_type1->name = 'id';
        $file_type1->save();
        
        $file_type2 = new FileType;
        $file_type2->code = '2';
        $file_type2->name = 'signed_agreement';
        $file_type2->save();
    
        $file_type3 = new FileType;
        $file_type3->code = '3';
        $file_type3->name = 'bill_of_sales';
        $file_type3->save();

        $file_type4 = new FileType;
        $file_type4->code = '4';
        $file_type4->name = 'proof_of_sales';
        $file_type4->save();

        $file_type5 = new FileType;
        $file_type5->code = '5';
        $file_type5->name = 'proof_of_income';
        $file_type5->save();

        $file_type6 = new FileType;
        $file_type6->code = '6';
        $file_type6->name = 'pad_form';
        $file_type6->save();

        $file_type7 = new FileType;
        $file_type7->code = '7';
        $file_type7->name = 'invoice';
        $file_type7->save();

        $file_type8 = new FileType;
        $file_type8->code = '8';
        $file_type8->name = 'certificate_of_completition';
        $file_type8->save();

        $file_type9 = new FileType;
        $file_type9->code = '9';
        $file_type9->name = 'void_cheque';
        $file_type9->save();
    
    }
}
