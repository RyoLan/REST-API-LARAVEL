<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Validator;

class GenreController extends Controller
{
    public function index()
    {

        $genre = Genre::latest()->get();
        $response = [
            'success' => true,
            'message' => 'Data Genre Film',
            'data' => $genre
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        // VALIDASI DATA
        $validate = Validator::make($request->all(), [
            'nama_genre' => 'required|unique:genres'
        ], [
            'nama_genre.required' => 'Masukan Genre',
            'nama_genre.unique' => 'genre Sudah Digunakan'
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
            $genre = new Genre();
            $genre->nama_genre = $request->nama_genre;
            $genre->save();
        }

        if ($genre) {
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
        $genre = Genre::find($id);

        if ($genre) {
            return response()->json([
                'success' => true,
                'message' => 'Detail genre',
                'data' => $genre
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'genre Tidak Ditemukan',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {

        // VALIDASI DATA
        $validate = Validator::make($request->all(), [
            'nama_genre' => 'required'
        ], [
            'nama_genre.required' => 'Masukan genre',
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
            $genre = Genre::find($id);
            $genre->nama_genre = $request->nama_genre;
            $genre->save();
        }

        if ($genre) {
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
        $genre = Genre::find($id);
        if ($genre) {
            $genre->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $genre->nama_genre . ' Berhasil Dihapus',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 400);

        }
    }

}
