<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->state([
                'email' => 'roman@mail.ru',
                'password' => Hash::make('123')
            ])
            ->count(1)
            ->create();
            
        User::factory()
            ->state([
                'email' => 'test@gmail.com',
                'password' => Hash::make('123')
            ])
            ->count(1)
            ->create();
            
        // DB::table('users')->insert([
        //     'name' => Str::random(10),
        //     'email' => Str::random(10).'@gmail.com',
        //     'password' => Hash::make('password'),
        // ]);
    }
}
