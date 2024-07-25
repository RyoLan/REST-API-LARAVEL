<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktor;
use Validator;


class AktorController extends Controller
{
    public function index()
    {

        $aktor = Aktor::latest()->get();
        $response = [
            'success' => true,
            'message' => 'Data Aktor',
            'data' => $aktor
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        // VALIDASI DATA
        $validate = Validator::make($request->all(), [
            'nama_aktor' => 'required|unique:aktors',
            'biodata' => 'required'
        ], [
            'nama_aktor.required' => 'Masukan Aktor',
            'nama_aktor.unique' => 'genre Sudah Digunakan',
            'biodata.required' => 'Masukan Biodata',
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
            $aktor = new Aktor();
            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->biodata = $request->biodata;
            $aktor->save();
        }

        if ($aktor) {
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
        $aktor = Aktor::find($id);

        if ($aktor) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Aktor',
                'data' => $aktor
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
            'nama_aktor' => 'required',
            'biodata' => 'required'
        ], [
            'nama_aktor.required' => 'Masukan Aktor',
            'biodata.required' => 'Masukan biodata',
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
            $aktor = Aktor::find($id);
            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->biodata = $request->biodata;
            $aktor->save();
        }

        if ($aktor) {
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
        $aktor = Aktor::find($id);
        if ($aktor) {
            $aktor->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $aktor->nama_aktor . ' Berhasil Dihapus',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 400);

        }
    }
}
