<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilBakesbangpolController extends Controller
{
    public function show($slug)
    {
        $data = [
            'tentang-kami' => [
                'title' => 'Tentang Kami',
                'content' => 'Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Kota Bandung merupakan lembaga yang berperan dalam pembinaan ideologi, wawasan kebangsaan, serta koordinasi politik dalam negeri untuk mendukung ketahanan nasional.'
            ],
            'tugas-fungsi' => [
                'title' => 'Tugas & Fungsi',
                'content' => '<ul><li>Perumusan kebijakan ...</li><li>Pelaksanaan pendidikan politik ...</li></ul>'
            ],
            'struktur-organisasi' => [
                'title' => 'Struktur Organisasi',
                'content' => '<img src="' . asset('img/struktur-bakesbangpol.png') . '" class="img-fluid">'
            ],
            'kegiatan' => [
                'title' => 'Kegiatan',
                'content' => 'Bakesbangpol aktif menyelenggarakan pendidikan politik, kemah partai, pembinaan ormas...'
            ],
            'galeri-dokumentasi' => [
                'title' => 'Galeri Dokumentasi',
                'content' => view('galeri')->render()
            ],
            'kontak' => [
                'title' => 'Kontak',
                'content' => '<strong>Alamat:</strong> Jl. Wastukancana No.2<br><strong>Email:</strong> bakesbangpol@bandung.go.id'
            ]
        ];

        if (!array_key_exists($slug, $data)) {
            abort(404);
        }

        return view('detail', [
            'title' => $data[$slug]['title'],
            'content' => $data[$slug]['content']
        ]);
    }
}
