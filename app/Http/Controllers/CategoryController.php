<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Supoort\Facades\Storage;


class CategoryController extends Controller
{
    Public function index(){
        //mengakses model kategori dan ditampung kedalam variable
        $rsetCategory = Kategori::all();
        return view('v_Category.index',compact('rsetCategory'));
    }

    Public function create(){
        $akategori = array('blank'=>'Pilih Kategori',
        'M'=>'Kategori Modal',
        'A'=>'Alat',
        'BHP'=>'Bahan Habis Pakai',
        'BTHP'=>'Bahan Tidak Habis Pakai'
        );
        return view('v_Category.create',compact('akategori'));
    }

    Public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'deskripsi'              => 'required',
            'kategori'              => 'required',
        ]);


        //create post
        Kategori::create([
            'deskripsi'          => $request->deskripsi,
            'kategori'          => $request->kategori,
        ]);

        //redirect to index
        return redirect()->route('category.index')->with(['success' => 'Data Berhasil Disimpan!']);
        
    }

    Public function show($id): View
    {
        $rsetCategory = Kategori::findOrFail($id);
        return view('v_Category.show', compact('rsetCategory'));
    }

    Public function edit($id) {
        $akategori = array('blank'=>'Pilih Kategori',
        'M'=>'Kategori Modal',
        'A'=>'Alat',
        'BHP'=>'Bahan Habis Pakai',
        'BTHP'=>'Bahan Tidak Habis Pakai'
        );

        $rsetCategory = Kategori::findOrFail($id);
        return view('v_Category.edit', compact('rsetCategory','akategori'));
    }

    public function update(Request $request, string $id)
{
    $request->validate([
        'deskripsi' => 'required',
        'kategori' => 'required',
    ]);

    $rsetCategory = Kategori::find($id);
    $rsetCategory->update($request->all());

    return redirect()->route('category.index')->with(['success' => 'Data Berhasil Diubah!']);
}

    Public function destroy($id): RedirectResponse
    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()){ 
            return redirect()->route('category.index')->with(['Gagal' => 'Gagal dihapus']);
        } else {
            $rsetCategory = Kategori::findOrFail($id);
            $rsetCategory->delete();
            return redirect()->route('category.index')->with(['Success' => 'Berhasil dihapus']);
        }
    }
}
