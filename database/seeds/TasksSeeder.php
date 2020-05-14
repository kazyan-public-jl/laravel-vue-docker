<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //何件でもいいですが適当に3件作りました。
        factory(App\Tasks::class, 3)->create();
    }
}
