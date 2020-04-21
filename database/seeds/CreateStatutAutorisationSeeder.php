<?php

use Illuminate\Database\Seeder;
use App\StatutAutorisation;

class CreateStatutAutorisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
        $this->createNew(1, 'active');
        $this->createNew(2, 'echue');
        $this->createNew(3, 'aucune');
     }

     private function createNew($code, $name) {
         StatutAutorisation::create([
           'code' => $code, 'name' => $name,
         ]);
     }
}
