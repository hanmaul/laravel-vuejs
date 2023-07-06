<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(Request $req)
    {
        $data = Mahasiswa::with(['fakultas','prodi'])->paginate(10);
        return $data;
    }

    public function store(Request $req)
    {
        $req->validate([
            'npm' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'fakultas_id' => 'required',
            'prodi_id' => 'required',
            'alamat' => 'required'
        ],[
            'required' => 'Wajib Diisi'
        ]);
        
        $mhs_id = $req->mahasiswa_id;
        $data = [
            'npm' => $req->npm,
            'nama' => $req->nama,
            'jenis_kelamin' => $req->jenis_kelamin,
            'fakultas_id' => $req->fakultas_id,
            'prodi_id' => $req->prodi_id,
            'alamat' => $req->alamat
        ];
        
        if($mhs_id){
            Mahasiswa::where('id',$mhs_id)->update($data);
        }else{
            Mahasiswa::create($data);
        }

        return back();
    }

    public function destroy(Request $req)
    {
        $mhs_id = $req->mahasiswa_id;

        $data = Mahasiswa::find($mhs_id);
        $data->delete();

        return [
            'status' => 'success'
        ];
    }
}
