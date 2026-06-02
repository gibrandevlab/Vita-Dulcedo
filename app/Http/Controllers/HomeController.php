<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mock data for "Kabar Panti" (Latest Activities)
        $latestNews = [
            [
                'title' => 'Sukacita Natal Bersama Volunteer',
                'date' => '25 Des 2025',
                'excerpt' => 'Perayaan Natal tahun ini berlangsung penuh sukacita dengan kehadiran volunteer dari berbagai daerah.',
                'image' => 'https://images.unsplash.com/photo-1543857778-c4a1a3e0b2eb?q=80&w=600&auto=format&fit=crop', // Placeholder
            ],
            [
                'title' => 'Berbagi Cerita bersama Komunitas',
                'date' => '15 Nov 2025',
                'excerpt' => 'Kegiatan sharing session bersama anak-anak panti yang mengajarkan arti keberanian dan mimpi.',
                'image' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=600&auto=format&fit=crop', // Placeholder
            ],
            [
                'title' => 'Bantuan Pendidikan Semester Genap',
                'date' => '10 Jan 2026',
                'excerpt' => 'Penyaluran dana pendidikan untuk anak-anak asuh dari daerah Bomomani dan sekitarnya.',
                'image' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=600&auto=format&fit=crop', // Placeholder
            ]
        ];

        // Mock stats data
        $stats = [
            'anak_asuh' => '30+',
            'pengurus' => '13+',
            'tahun_berdiri' => '10+',
        ];

        return view('index', compact('latestNews', 'stats'));
    }

    public function about()
    {
        return view('about');
    }
}
