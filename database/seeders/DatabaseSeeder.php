<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $users = [
            [
                'name' => 'Samuel',
                'email' => 'samuel@hotmail.com',
                'password' => bcrypt('1234567890'),
                'created_at' => "2013-09-09",
            ],
        ];

        foreach($users as $user)
        {

            User::create($user);

        }

        $events = [

            [

                'title' => 'Evento #1',
                'start' => '2024-1-1 8:00',
                'end' => '2024-1-1 9:00',
                'description' => "Descripcion Del Evento 1"

            ],
            [

                'title' => 'Evento #2',
                'start' => '2024-1-2 8:00',
                'end' => '2024-1-2 9:00',
                'description' => "Descripcion Del Evento 2"

            ],
            [

                'title' => 'Evento #3',
                'start' => '2024-1-3 8:00',
                'end' => '2024-1-3 9:00',
                'description' => "Descripcion Del Evento 3"

            ],
        ];

        foreach($events as $event)
        {

            Event::create($event);

        }
    }
}
