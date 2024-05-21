<?php

namespace App\Http\Controllers\Engine\Kurs;

use App\Exports\KursExport;
use App\Http\Controllers\Controller;
use App\Imports\KursImport;
use App\Models\WebKurs;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KursController extends Controller
{
    public function index()
    {
        $data = WebKurs::all();

        return view('engine.module.kurs.index', compact('data'));
    }

    public function create()
    {
        return view('engine.module.kurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required', // 'country' is a column name in 'web_kurs' table
            'currency' => 'required',
            'bn_buy' => 'required',
            'bn_sell' => 'required',
            'image' => 'required',
        ]);

        $data = $request->all();

        $data = WebKurs::create($data);

        return redirect()->route('kurs')->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $data = WebKurs::findOrFail($id);

        return view('engine.module.kurs.edit', compact('data'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'country' => 'required',
            'currency' => 'required',
            'bn_buy' => 'required',
            'bn_sell' => 'required',
            'image' => 'required',
        ]);

        $data = $request->all();

        $item = WebKurs::findOrFail($request->id);

        $item->update($data);

        return redirect()->route('kurs')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Request $request)
    {
        $item = WebKurs::findOrFail($request->id);

        $item->delete();

        return redirect()->route('kurs')->with('success', 'Data berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new KursExport, 'kurs-'.date('Y-m-d-H-i-s').'.xlsx');
    }

    public function import()
    {
        try {
            WebKurs::truncate();
            Excel::import(new KursImport, request()->file('kurs'));

            return redirect()->route('kurs')->with('success', 'Data berhasil diimport');
        } catch (\Throwable $th) {
            return $th;
            return redirect()->route('kurs')->with('error', 'Data gagal diimport');
        }

    }
}
