<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
////////////////
        {
            \Eloquent::unguard();
            $this->call(UsersTablesSeeder::class);
        }


////////////////
        \App\Models\User::factory()->create([
            'name' => 'mitchell',
            'email' => 'mitchell@thedarecompany.com',
            'account_type' => '1',
            'password' => \Hash::make('test'),
        ]);

         \App\Models\User::factory(10)->create();


    }
}
