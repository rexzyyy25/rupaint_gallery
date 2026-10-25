<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Painter;
use App\Models\Painting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@rupaint.local'],
            [
                'name' => 'Admin Rupaint',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );
        if (! $admin->is_admin) {
            $admin->forceFill(['is_admin' => true])->save();
        }

        $painter = Painter::firstOrCreate(
            ['email' => 'artist@rupaint.local'],
            [
                'name' => 'Rupaint Artist',
                'phone' => '6281232651799',
                'bio' => 'Pelukis dan kreator karya visual Rupaint Gallery.',
            ]
        );

        $categories = [];
        foreach (['Portrait', 'Landscape', 'Abstract', 'Nature', 'Custom'] as $name) {
            $categories[$name] = Category::firstOrCreate(['name' => $name]);
        }

        $works = [
            ['Senja di Ujung Kota', 'Suasana kota yang hangat saat matahari tenggelam.', 'Landscape'],
            ['Wajah Dalam Cahaya', 'Eksplorasi karakter dan cahaya dalam sebuah potret.', 'Portrait'],
            ['Ruang Imajinasi', 'Komposisi abstrak penuh gerak dan kemungkinan.', 'Abstract'],
            ['Hijau yang Tenang', 'Interpretasi alam dengan warna-warna lembut.', 'Nature'],
            ['Bunga Setelah Hujan', 'Bunga-bunga cerah yang mekar seusai hujan.', 'Nature'],
            ['Laut Bercerita', 'Lapisan biru dan turquoise dari laut yang hidup.', 'Landscape'],
            ['Mimpi Berwarna', 'Bentuk-bentuk ekspresif dalam warna yang berani.', 'Abstract'],
            ['Perempuan dan Matahari', 'Potret hangat tentang kekuatan dan harapan.', 'Portrait'],
            ['Hutan Pagi', 'Cahaya pagi menembus rimbun pepohonan.', 'Nature'],
            ['Langit Penuh Cerita', 'Sapuan warna langit senja yang dramatis.', 'Landscape'],
            ['Irama Kota', 'Energi kota dalam bentuk dan warna geometris.', 'Abstract'],
            ['Senyum di Taman', 'Potret sederhana dengan suasana taman yang ceria.', 'Portrait'],
        ];

        foreach ($works as [$title, $description, $category]) {
            Painting::firstOrCreate(
                ['painter_id' => $painter->id, 'title' => $title],
                [
                    'category_id' => $categories[$category]->id,
                    'description' => $description,
                    'size' => '40 x 60 cm',
                    'material' => 'Acrylic on canvas',
                    'year' => 2026,
                    'price' => 100000,
                    'status' => 'available',
                ]
            );
        }
    }
}
