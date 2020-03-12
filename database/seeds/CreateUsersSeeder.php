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
        $user = [
            [
                'name'=>'Admin',
                'email'=>'admin@itslogistik.com',
                'role'=>'admin',
                'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'Eksekutif',
               'email'=>'eksekutif@itslogistik.com',
               'role'=>'eksekutif',
               'password'=> bcrypt('123456'),
            ],
        ];
  
        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
