<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Ada Wine', 'image' => '/images/specialists/specialist1.jpg', 'phone'=>'79493333333',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Amet est placerat in egestas erat imperdiet sed. Nisl suscipit adipiscing bibendum est. Volutpat consequat mauris nunc congue nisi vitae. In cursus turpis massa tincidunt dui ut ornare lectus. Elementum nibh tellus molestie nunc non blandit massa enim nec. Vel pharetra vel turpis nunc eget lorem dolor sed. Quam quisque id diam vel quam elementum. Semper viverra nam libero justo laoreet sit. Etiam non quam lacus suspendisse faucibus. Pharetra vel turpis nunc eget lorem dolor sed viverra. Urna et pharetra pharetra massa massa ultricies mi quis hendrerit. Tortor vitae purus faucibus ornare suspendisse. Senectus et netus et malesuada fames ac. Suspendisse faucibus interdum posuere lorem. Id neque aliquam vestibulum morbi blandit cursus. Senectus et netus et malesuada.'],
            ['name'=>'Amanda Smart', 'image'=>'/images/specialists/specialist2.jpeg','phone'=>'79491111111',
                'description'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'],
            ['name' => 'Stela King', 'image' => '/images/specialists/specialist3.jpg','phone'=>'7949222222',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Amet est placerat in egestas erat imperdiet sed. Nisl suscipit adipiscing bibendum est. Volutpat consequat mauris nunc congue nisi vitae. In cursus turpis massa tincidunt dui ut ornare lectus. Elementum nibh tellus molestie nunc non blandit massa enim nec. Vel pharetra vel turpis nunc eget lorem dolor sed. Quam quisque id diam vel quam elementum. Semper viverra nam libero justo laoreet sit. Etiam non quam lacus suspendisse faucibus.'],
            ['name' => 'Lina Loss', 'image' => '/images/specialists/specialist4.jpg', 'description'=>null,'phone'=>'7949444444',]
        ];

        foreach ($users as $user){
            $specialist = User::create([
                'name'=>$user['name'],
                'phone'=>$user['phone'],
                'password'=>Hash::make($user['phone']),
                'image'=>$user['image'],
                'description'=>$user['description']
            ]);
            $specialist->assignRole('specialist');
        }
    }
}
