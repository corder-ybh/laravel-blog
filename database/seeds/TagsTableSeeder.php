<?php
/**
 * Created by PhpStorm.
 * User: YBH
 * Date: 2019/9/7
 * Time: 12:58
 */
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();

        factory(Tag::class, 5)->create();
    }
}