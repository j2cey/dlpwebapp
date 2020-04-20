<?php

use Illuminate\Database\Seeder;
use App\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(['name' => "rootdev",'email' => "rootdev@gestockdsi.com",'password' => bcrypt('Gestock@Pw0rd')]);
        $user = User::create(['name' => "admin",'email' => "admin@gt.com",'password' => bcrypt('admin123')]);
    }
}
