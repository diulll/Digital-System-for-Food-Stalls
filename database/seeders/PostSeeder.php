<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if ($user) {
            $posts = [
                [
                    'user_id' => $user->id,
                    'title' => 'Selamat Datang di Sistem Warung Makan Digital',
                    'content' => 'Sistem ini membantu Anda mengelola warung makan dengan lebih efisien.',
                ],
                [
                    'user_id' => $user->id,
                    'title' => 'Cara Menggunakan Sistem',
                    'content' => 'Panduan lengkap untuk menggunakan fitur-fitur yang tersedia di sistem warung makan digital.',
                ],
                [
                    'user_id' => $user->id,
                    'title' => 'Tips Mengelola Warung Makan',
                    'content' => 'Beberapa tips dan trik untuk meningkatkan efisiensi operasional warung makan Anda.',
                ],
            ];

            foreach ($posts as $post) {
                Post::create($post);
            }
        }
    }
}
