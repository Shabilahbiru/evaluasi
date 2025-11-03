<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\DataPemilih;

class DataPemilihScanController extends Controller
{
    public function uploadDPT(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'jenis_pemilu' => 'required|string',
        ]);

        $filePath = $request->file('image')->getPathname();

        $response = Http::asMultipart()->post('https://api.ocr.space/parse/image', [
            [
                'name' => 'file',
                'contents' => fopen($filePath, 'r'),
                'filename' => $request->file('image')->getClientOriginalName(),
            ],
            [
                'name' => 'apikey',
                'contents' => config('services.ocr.key'),
            ],
            [
                'name' => 'language',
                'contents' => 'eng',
            ],
            [
                'name' => 'isOverlayRequired',
                'contents' => 'false',
            ],
        ]);

        $result = $response->json();
        $text = $result['ParsedResults'][0]['ParsedText'] ?? '';
        file_put_contents(storage_path('app/ocr-api-dpt.txt'), $text);

        $parsedData = $this->extractDPTData($text, $request->jenis_pemilu);

        session(['ocr_dpt' => $parsedData]);

        return back()->with('success', 'Data DPT berhasil diproses. Sekarang upload gambar suara.');
    }

    public function uploadSuara(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        $filePath = $request->file('image')->getPathname();

        $response = Http::asMultipart()->post('https://api.ocr.space/parse/image', [
            [
                'name' => 'file', 
                'contents' => fopen($filePath, 'r'),
                'filename' => $request->file('image')->getClientOriginalName(),
            ],
            [
                'name' => 'apikey',
                'contents' => config('services.ocr.key'),
            ],
            [
                'name' => 'language',
                'contents' => 'eng',
            ],
            [
                'name' => 'isOverlayRequired',
                'contents' => 'false',
            ]
            ]);

            $result = $response->json();
            $text = $result['ParsedResults'][0]['ParsedText'] ?? '';

            file_put_contents(storage_path('app/ocr-api-suara.txt'), $text);

            $suaraData = $this->extractSuaraData($text);
            $dptData = session('ocr_dpt', []);

            $finalData = $this->gabungkanData($dptData, $suaraData);

            session(['ocr_preview' => $finalData]);

            return view('data_pemilih.ocr_preview_editable', ['previewData' => $finalData]);
    }

    private function extractDPTData($text, $jenispemilu)
    {
        $kecamatanList = [
            'ANDIR', 'ANTAPANI', 'ARCAMANIK', 'ASTANA ANYAR', 'BABAKAN CIPARAY',
            'BANDUNG KIDUL', 'BANDUNG KULON', 'BANDUNG WETAN', 'BATUNUNGGAL',
            'BOJONGLOA KALER', 'BOJONGLOA KIDUL', 'BUAH BATU', 'CIBEUNYING KALER',
            'CIBEUNYING KIDUL', 'CIBIRU', 'CICENDO', 'CIDADAP', 'CINAMBO', 
            'COBLONG', 'GEDEBAGE', 'KIARA CONDONG', 'LENGKONG', 'MANDALAJATI',
            'PANYILEUKAN', 'RANCASARI', 'REGOL', 'SUKAJADI', 'SUKASARI',
            'SUMUR BANDUNG', 'UJUNG BERUNG'
        ];  

        $lines = preg_split('/\s+/', strtoupper($text));
        $data = [];
        
        for ($i = 0; $i <count($lines); $i++) {
            $word = trim($lines[$i]);

            foreach ($kecamatanList as $kec) {
                similar_text($word, $kec, $percent);
                if ($percent >= 80) {
                    $dptL = preg_replace('/[^\d]/', '', $lines[$i + 1] ?? '0');
                    $dptP = preg_replace('/[^\d]/', '', $lines[$i + 2] ?? '0');
                    $dptTotal = preg_replace('/[^\d]/', '', $lines[$i + 3] ?? '0');

                    if ($dptL === '' && $dptP === '' && $dptTotal === '') continue; 

                    $dptL = (int) $dptL;
                    $dptP = (int) $dptP;
                    $dptTotal = (int) $dptTotal;

                    file_put_contents(storage_path("app/ocr-debug-{$kec}.txt"), implode("\n", array_slice($lines, $i, 5)));

                    $data[] = [
                    'kecamatan' => $kec,
                    'dpt_laki_laki' => $dptL,
                    'dpt_perempuan' => $dptP,
                    'dpt_total' => $dptTotal > 0 ? $dptTotal : $dptL + $dptP,
                    'suara_sah' => 0,
                    'suara_tidak_sah' => 0,
                    'suara_total' => 0,
                    'partisipasi' => 0,
                    'jenis_pemilu' => $jenispemilu,
                ];

                break;
                    
                }
            }
        }
        return $data;
    }

    private function extractSuaraData($text)
    {
        $kecamatanList = [
            'ANDIR', 'ANTAPANI', 'ARCAMANIK', 'ASTANA ANYAR', 'BABAKAN CIPARAY',
            'BANDUNG KIDUL', 'BANDUNG KULON', 'BANDUNG WETAN', 'BATUNUNGGAL',
            'BOJONGLOA KALER', 'BOJONGLOA KIDUL', 'BUAH BATU', 'CIBEUNYING KALER',
            'CIBEUNYING KIDUL', 'CIBIRU', 'CICENDO', 'CIDADAP', 'CINAMBO', 
            'COBLONG', 'GEDEBAGE', 'KIARA CONDONG', 'LENGKONG', 'MANDALAJATI',
            'PANYILEUKAN', 'RANCASARI', 'REGOL', 'SUKAJADI', 'SUKASARI',
            'SUMUR BANDUNG', 'UJUNG BERUNG'
        ];  

        $lines = preg_split('/\s+/', strtoupper($text));
        $data = [];
        $seen = [];

        for ($i = 0; $i < count($lines); $i++) {
            $word = trim($lines[$i]);
            foreach ($kecamatanList as $kec) {
                similar_text($word, $kec, $percent);
                if ($percent >= 80 && !isset($seen[$kec])) {
                    $sah = preg_replace('/\D/', '', $lines[$i + 1] ?? '0');
                    $tidaksah = preg_replace('/\D/', '', $lines[$i + 2] ?? '0');
                    $total = preg_replace('/\D/', '', $lines[$i + 3] ?? '0');
                    $data[$kec] = [
                        'suara_sah' => (int) $sah,
                        'suara_tidak_sah' => (int) $tidaksah,
                        'suara_total' => (int) $total > 0 ? (int) $total : ((int) $sah + (int) $tidaksah),
                    ];

                    $seen[$kec] = true;

                    file_put_contents(
                        storage_path("app/ocr-debug-suara-{$kec}.txt"),
                        implode("\n", array_slice($lines, $i, 4))
                    );

                    $i += 3;
                    break;
                }
            }
        }

        return $data;

    }


    private function gabungkanData($dpt, $suara)
    {
        $final = [];
        foreach ($dpt as &$item) {
            $kec = $item['kecamatan'];
            $item['suara_sah'] = $suara[$kec]['suara_sah'] ?? 0;
            $item['suara_tidak_sah'] = $suara[$kec]['suara_tidak_sah'] ?? 0;
            $item['suara_total'] = $suara[$kec]['suara_total'] ?? 0;
            $dptTotal = $item['dpt_total'] ?? 0;
            $suaraTotal = $item['suara_total'] ?? 0;
            $item['partisipasi'] = $dptTotal > 0 ? round(($suaraTotal / $dptTotal) * 100, 2) : 0;
            $final[] = $item;
        }
        return array_values($final);
    }
 
    public function confirmSave(Request $request)
    {
        $items = json_decode($request->input('data'), true);

        foreach ($items as $item) {
            DataPemilih::create($item);
        }

        return redirect()->route('data-pemilih.index')->with('success', 'Semua data hasil OCR berhasil disimpan');
    }
}
