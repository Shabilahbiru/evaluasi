<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DataPemilih;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataPemilihImport; 
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;
use Intervention\Image\Facades\Image;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\UserActivity;
use Illuminate\Database\Eloquent\Builder;

class DataPemilihController extends Controller
{
    public function index(Request $request)
    {
        $query = DataPemilih::query();

        $jenisPemilu = $request->input('jenis_pemilu', session('jenis_pemilu', 'Presiden'));
        $query->where('jenis_pemilu', $jenisPemilu);
        $jenisPemiluList = DataPemilih::select('jenis_pemilu')->distinct()->pluck('jenis_pemilu');

        // $data_pemilih = $query->get();

        $perPage = $request->input('per_page', 10);
        $data_pemilih = $query->paginate($perPage);

        return view('data_pemilih.index', compact('data_pemilih', 'jenisPemilu', 'jenisPemiluList'));
    }

    public function create()
    {
        return view('data_pemilih.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'dpt_laki_laki' => 'required|integer',
            'dpt_perempuan' => 'required|integer',
            'suara_sah' => 'required|integer',
            'suara_tidak_sah' => 'required|integer',
            'jenis_pemilu' => 'required|string',
            'scan_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $dpt_total = $request->dpt_laki_laki + $request->dpt_perempuan;
        $suara_total = $request->suara_sah +$request->suara_tidak_sah;
        $partisipasi = $dpt_total > 0 ? ($suara_total / $dpt_total) * 100 : 0;

        $data = [
            'kecamatan' => $request->kecamatan, 
            'dpt_laki_laki' => $request->dpt_laki_laki,
            'dpt_perempuan' => $request->dpt_perempuan,
            'dpt_total'=> $dpt_total,
            'suara_sah' => $request->suara_sah,
            'suara_tidak_sah' => $request->suara_tidak_sah,
            'suara_total' => $suara_total,
            'partisipasi' => round($partisipasi, 2),
            'jenis_pemilu' => $request->jenis_pemilu,
        ];

        if ($request->hasFile('scan_file')) {
            $file = $request->file('scan_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/scan'), $filename);
            $data['scan_file'] = $filename;
        }   

        $created = DataPemilih::create($data);

        $user = auth()->user();
        if ($user && is_numeric($user->id)) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Menambahkan data pemilih untuk kecamatan ' . $created->kecamatan, 
            ]);
        }

        return redirect()->route('data-pemilih.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function importForm()
    {
        return view('data_pemilih.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
            'jenis_pemilu' => 'required|string'
        ]);


        Excel::import(new DataPemilihImport($request->jenis_pemilu), $request->file('file')); // Pass the required argument to the import class

        $user = auth()->user();
        if ($user && is_numeric($user->id)) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Mengimpor data pemilih dari file Excel untuk jenis pemilu: ' . $request->jenis_pemilu,
            ]);
        }
      
        return redirect()->route('data-pemilih.index')->with('success', 'Data berhasil diimpor!');
    }


    public function edit($id)
    {

        $data = DataPemilih::findOrFail($id);
        return view('data_pemilih.edit', compact('data'));

    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'kecamatan' => 'required|string',
            'dpt_laki_laki' => 'required|integer',
            'dpt_perempuan' => 'required|integer',
            'dpt_total' => 'required|integer',
            'suara_sah' => 'required|integer',
            'suara_tidak_sah' => 'required|integer',
            'suara_total' => 'required|integer',
            'partisipasi' => 'required|numeric',
        ]);

        $dpt_total = $request->dpt_laki_laki + $request->dpt_perempuan;
        $suara_total = $request->suara_sah + $request->suara_tidak_sah;
        $partisipasi = $dpt_total > 0 ? ($suara_total / $dpt_total) * 100 : 0;

        $data = DataPemilih::findOrFail($id);
        $data->update ([
            'kecamatan' => $request->kecamatan, 
            'dpt_laki_laki' => $request->dpt_laki_laki,
            'dpt_perempuan' => $request->dpt_perempuan,
            'dpt_total' => $dpt_total,
            'suara_sah' => $request->suara_sah,
            'suara_tidak_sah' => $request->suara_tidak_sah,
            'suara_total' => $suara_total,
            'partisipasi' => round($partisipasi, 2)
        ]);

        return redirect()->route('data-pemilih.index')->with('success', 'Data berhasil diperbaharui.');
    }

    public function destroy($id)
    {

        $data = DataPemilih::findOrFail($id);
        $kecamatan = $data->kecamatan;
        $data->delete();

        $user = auth()->user();
        if ($user && is_numeric($user->id)) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Menghapus data pemilih untuk kecamatan ' . $kecamatan,
            ]);
        }

        return redirect()->route('data-pemilih.index')->with('success', 'Data berhasil dihapus.');
    }

}