<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    {
        $labels = ['FrontEnd', 'BackEnd', 'UI/UX', 'Fullstack', 'Design'];

        foreach($labels as $label){
            $type = new Type();

            $type->label = $label;
            $type->color = $faker->hexColor();

            $type->save();
        }
    }
}
