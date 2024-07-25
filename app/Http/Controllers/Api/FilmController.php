<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;
use Validator;
use Storage;

class FilmController extends Controller
{

    public function index(){
        $films = Film::with(['genre', 'aktor'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Data Film',
            'data'=> $films,
        ], 200);
    }

    public function store(Request $request){
        $validate = Validator::make($request->all(), [
            'judul'=> 'required|string|unique:films',
            'deskripsi'=> 'required|string',
            'foto'=> 'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'url_vidio'=> 'required|string',
            'id_kategori'=> 'required|exists:kategoris,id',
            'genre'=> 'required',
            'aktor'=> 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'success'=> false,
                'message'=> 'Validasi Gagal',
                'errors' => $validate->errors()
            ], 400);
        }

        try {
            $path = $request->file('foto')->store('public/foto');

            $film = Film::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'foto' => $path,
                'url_vidio' => $request->url_vidio,
                'id_kategori' => $request->id_kategori,
            ]);

            $film->genre()->attach($request->genre);
            $film->aktor()->attach($request->aktor);

            return response()->json([
                'success'=> true,
                'message'=> 'Data Berhasil Disimpan',
                'data'=> $film
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Terjadi Kesalahan',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id){
        try {
            $film = Film::with(['genre', 'aktor'])->findOrFail($id);
            return response()->json([
                'success'=> true,
                'message'=> 'Detail Film',
                'data'=> $film
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Data Tidak Ditemukan',
                'errors'=> $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id){
        $film = Film::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'judul'=> 'required|string|unique:films' . $id,
            'deskripsi'=> 'required|string',
            'foto'=> 'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'url_vidio'=> 'required|string',
            'id_kategori'=> 'required|exists:kategoris,id',
            'genre'=> 'required|array',
            'aktor'=> 'required|array',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success'=> false,
                'message'=> 'Validasi Gagal',
                'errors'=> $validate->errors()
            ],422);
        }

        try {
            if ($request->hasFile('foto')) {
                // delete old foto
                Storage::delete($request->file('foto')->store('public/foto'));

                $path = $request->file('foto')->store('public/foto');
                $film->foto = $path;
            }

            $film->update($request->only(['judul', 'deskripsi', 'url_vidio', 'id_kategori']));

            if ($request->has('genre')) {
                $film->genre()->sync($request->genre);
            }
            if ($request->has('aktor')) {
                $film->aktor()->sync($request->aktor);
            }

            return response()->json([
                'success'=> true,
                'message'=> 'Data Berhasil Diperbaharui',
                'data' => $film,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'An Error occurred',
                'errors'=> $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id){
        try {
            $film = Film::findOrFail($id);

            // delete foto
            Storage::delete($film->foto);

            $film->delete();

            return response()->json([
                'success'=> true,
                'message'=> 'Data Berhasil Dihapus',
                'data'=> null,
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Data Not Found',
                'errors'=> $e->getMessage(),
            ], 404);
        }
    }

}
