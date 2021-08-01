<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::truncate();
        $team_1 = Team::create([
            'name' => 'testGroupNoToto 1'
        ]);
        $team_1->users()->attach(1, ['admin' => true]);
        $team_1->users()->attach(3, ['admin' => true]);
        $team_1->users()->attach(2);
        $team_1->save();

        $team_2 = Team::create([
            'name' => 'testGroupNoToto 2'
        ]);
        $team_2->users()->attach(2, ['admin' => true]);
        $team_2->users()->attach(3);
        $team_2->save();

        $team_3 = Team::create([
            'name' => 'testGroupNoToto 3'
        ]);
        $team_3->users()->attach(7, ['admin' => true]);
        $team_3->users()->attach(8);
        $team_3->save();
    }
}
