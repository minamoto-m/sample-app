<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // AuthorsTableSeederファイルをシーディングの対象にする
        // $this->call(AuthorsTableSeeder::class);

        // CategoriesTableSeederファイルをシーディングの対象にする
        // $this->call(CategoriesTableSeeder::class);
        
        $this->call([
            AuthorsTableSeeder::class,
            BooksTableSeeder::class,
            AuthorBookTableSeeder::class,
        ]);

    }
}
