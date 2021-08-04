<?php

namespace Database\Seeders;

use App\Models\Happening;
use Illuminate\Database\Seeder;

class HappeningsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Happening::truncate();
        $start = new \DateTime('2021-11-10 11:21:50');
        $end = new \DateTime('2021-11-10 12:21:50');
        $test1 = Happening::create([
            'name' => 'test event 1',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end
        ]);
        $test1->members()->attach(1);
        $test1->members()->attach(2);

        $start = new \DateTime('2021-04-25 11:21:50');
        $end = new \DateTime('2021-04-25 12:21:50');
        $test2 = Happening::create([
            'name' => 'test event 2',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end
        ]);
        $test2->members()->attach(1);
        $test2->members()->attach(2);

        $start = new \DateTime('2021-11-30 11:21:50');
        $end = new \DateTime('2021-11-30 12:21:50');
        $test3 = Happening::create([
            'name' => 'test event group 2',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 2,
            'start_at' => $start,
            'end_at' => $end
        ]);
        $test3->members()->attach(2);
        $test3->members()->attach(3);
    }
}
