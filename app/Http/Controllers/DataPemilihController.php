<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DataPemilih;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataPemilihImport; // Ensure this class exists in the specified namespace

class DataPemilihController extends Controller
{
    public function index()
    {
        $data_pemilih = DataPemilih::paginate(10);
        return view('data_pemilih.index', compact('data_pemilih'));
    }

    public function create()
    {
        return view('data_pemilih.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'dpt_laki_laki' => 'required|integer',
            'dpt_perempuan' => 'required|integer',
            'dpt_total' => 'required|integer',
            'suara_sah' => 'required|integer',
            'suara_tidak_sah' => 'required|integer',
            'suara_total' => 'required|integer',
            'partisipasi' => 'required|numeric',
        ]);

        $dpt_total = $request->dpt_laki_laki + $request->dpt_perempuan;
        $suara_total = $request->suara_sah +$request->suara_tidak_sah;
        $partisipasi = $dpt_total > 0 ? ($suara_total / $dpt_total) * 100 : 0;

        $exists = DataPemilih::where('kecamatan', $request->kecamatan)
                     ->where('kelurahan', $request->kelurahan)
                     ->exists();

        if ($exists) {
             return redirect()->back()
                ->withErrors(['Data untuk kecamatan dan kelurahan ini sudah ada.'])
                ->withInput();
        }


        DataPemilih::create([
            'kecamatan' => $request->kecamatan, 
            'kelurahan' => $request->kelurahan, 
            'dpt_laki_laki' => $request->dpt_laki_laki,
            'dpt_perempuan' => $request->dpt_perempuan,
            'dpt_total'=> $dpt_total,
            'suara_sah' => $request->suara_sah,
            'suara_tidak_sah' => $request->suara_tidak_sah,
            'suara_total' => $suara_total,
            'partisipasi' => round($partisipasi, 2)
        ]);

        return redirect()->route('data-pemilih.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function importForm()
    {
        return view('data-pemilih.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        Excel::import(new DataPemilihImport(), $request->file('file')); // Ensure the class is instantiated correctly

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
            'kelurahan' => 'required|string',
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
            'kelurahan' => $request->kelurahan, 
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
        $data->delete();

        return redirect()->route('data-pemilih.index')->with('success', 'Data berhasil dihapus.');
    }

}