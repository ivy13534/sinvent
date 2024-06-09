<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class BarangController extends Controller
{
    public function index(){
        $rsetBarang = barang::all();
        $namaProduk = barang::with('kategori')->get();
        return view('v_barang.index',compact('rsetBarang', 'namaProduk'));
    }

    Public function create(){
        $kategori = Kategori::all();

        $aKategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Barang Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );
        return view('v_barang.create',compact('aKategori', 'kategori'));

    }

    Public function store(Request $request):  RedirectResponse
    {
        $request->validate([
            'merk'              => 'required',
            'seri'              => 'required',
            'spesifikasi'       => 'required',
            'kategori_id'          => 'required',
        ]);


        //create post
        Barang::create([
            'merk'          => $request->merk,
            'seri'          => $request->seri,
            'spesifikasi'   => $request->spesifikasi,
            'stok'      => $request->stok,
            'kategori_id'      => $request->kategori_id,
        ]);

        //redirect to index
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    Public function show($id): View
    {
        $rsetBarang = barang::findOrFail($id); // Fetch the specific barang instance    
        return view('v_barang.show', compact('rsetBarang'));
    }
    
    Public function edit($id)
    {
        $rsetBarang = barang::findOrFail($id);
        $kategoriID = kategori::all();

        return view('v_barang.edit', compact('rsetBarang', 'kategoriID'));
    }

    Public function update(Request $request, $id)
    {
        $request->validate([
            'merk' => 'required',
            'seri' => 'required',
            'spesifikasi' => 'required',
            'stok' => 'required',
            'kategori_id' => 'required',
        ]);
    
        $rsetBarang = Barang::find($id);
        $rsetBarang->update($request->all());
    
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    Public function destroy($id): RedirectResponse
    {
        if (DB::table('barangmasuk')->where('barang_id', $id)->exists() || DB::table('barangkeluar')->where('barang_id', $id)->exists()) {
            return redirect()->route('barang.index')->with(['Gagal' => 'Gagal dihapus']);
        } else {
            $rsetBarang = Barang::find($id);
            $rsetBarang->delete();
            return redirect()->route('barang.index')->with(['Success' => 'Berhasil dihapus']);
        }
    }
}
