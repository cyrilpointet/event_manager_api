<?php

namespace Database\Seeders;

use App\Models\Happening;
use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Survey::truncate();

        $survey = Survey::create([
            'team_id' => 1
        ]);

        $start = new \DateTime('2021-12-10 11:21:50');
        $end = new \DateTime('2021-12-10 12:21:50');
        $happening = Happening::create([
            'name' => 'test survey',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end,
            'survey_id' => $survey->id
        ]);
        $happening->members()->attach(1);
        $happening->members()->attach(2);
        $happening->members()->attach(3);
        $happening->members()->attach(4);

        $start = new \DateTime('2021-12-11 11:21:50');
        $end = new \DateTime('2021-12-11 12:21:50');
        $happening = Happening::create([
            'name' => 'test survey',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end,
            'survey_id' => $survey->id
        ]);
        $happening->members()->attach(1);
        $happening->members()->attach(2);
        $happening->members()->attach(3);
        $happening->members()->attach(4);

        $start = new \DateTime('2021-12-12 11:21:50');
        $end = new \DateTime('2021-12-12 12:21:50');
        $happening = Happening::create([
            'name' => 'test survey',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end,
            'survey_id' => $survey->id
        ]);
        $happening->members()->attach(1);
        $happening->members()->attach(2);
        $happening->members()->attach(3);
        $happening->members()->attach(4);

        $start = new \DateTime('2021-12-13 11:21:50');
        $end = new \DateTime('2021-12-13 12:21:50');
        $happening = Happening::create([
            'name' => 'test survey',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end,
            'survey_id' => $survey->id
        ]);
        $happening->members()->attach(1);
        $happening->members()->attach(2);
        $happening->members()->attach(3);
        $happening->members()->attach(4);

        $start = new \DateTime('2021-12-14 11:21:50');
        $end = new \DateTime('2021-12-14 12:21:50');
        $happening = Happening::create([
            'name' => 'test survey',
            'description' => 'description test',
            'place' => 'place test',
            'team_id' => 1,
            'start_at' => $start,
            'end_at' => $end,
            'survey_id' => $survey->id
        ]);
        $happening->members()->attach(1);
        $happening->members()->attach(2);
        $happening->members()->attach(3);
        $happening->members()->attach(4);
    }
}
