<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Validator;

class KategoriController extends Controller
{
    public function index()
    {

        $kategori = Kategori::latest()->get();
        $response = [
            'success' => true,
            'message' => 'Data Kategori',
            'data' => $kategori
        ];
        return response()->json($response, 200);
    }
    
    public function store(Request $request)
    {
        // VALIDASI DATA
        $validate = Validator::make($request->all(), [
            'nama_kategori' => 'required|unique:Kategoris'
        ], [
            'nama_kategori.required' => 'Masukan Kategori',
            'nama_kategori.unique' => 'Kategori Sudah Digunakan'
        ]);

        // application/json (API Eror Middleware)
        // JSON Javascript Objeck

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Slahkan isi dengan benar',
                'data' => $validate->errors(),
            ], 400);
        } else {
            $kategori = new Kategori;
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
        }

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
            ], 400);
        }
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Kategori',
                'data' => $kategori
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {

        // VALIDASI DATA
        $validate = Validator::make($request->all(), [
            'nama_kategori' => 'required'
        ], [
            'nama_kategori.required' => 'Masukan Kategori',
        ]);

        // accept application/json (API Eror Middleware)
        // JSON Javascript Objeck

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Slahkan isi dengan benar',
                'data' => $validate->errors(),
            ], 400);
        } else {
            $kategori = Kategori::find($id);
            $kategori->nama_kategori = $request->nama_kategori;
            $kategori->save();
        }

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diperbaharui',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
            ], 400);
        }
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        if ($kategori) {
            $kategori->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $kategori->nama_kategori . ' Berhasil Dihapus',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 400);

        }
    }

}

// 200 evriting is ok
// 201
// 404 Not Found
