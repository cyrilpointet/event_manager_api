<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::truncate();

        Comment::create([
            'content' => 'lorem ipsum test comment',
            'happening_id' => 1,
            'user_id' => 1
        ]);

        Comment::create([
            'content' => 'lorem ipsum test comment user 2',
            'happening_id' => 1,
            'user_id' => 2
        ]);
    }
}
