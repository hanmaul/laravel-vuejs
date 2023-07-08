<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class ApiMahasiswaController extends Controller
{
    public function get()
    {
        $data = Mahasiswa::with(['fakultas', 'prodi'])->paginate(10);
        if($data){
            return $data;
        }else{
            return 'Data Kosong!';
        }
    }
    
    public function save(Request $req)
    {
        $req->validate([
            'npm' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'fakultas_id' => 'required',
            'prodi_id' => 'required',
            'alamat' => 'required'
        ], [
            'required' => 'Wajib Diisi'
        ]);

        // insert input(data) to variable
        $mhs_id = $req->mahasiswa_id;
        $npm = $req->npm;
        $nama = $req->nama;
        $jenis_kelamin = $req->jenis_kelamin;
        $fakultas_id = $req->fakultas_id;
        $prodi_id = $req->prodi_id;
        $alamat = $req->alamat;

        // check request mahasiswa_id, true = update, false = create
        if($mhs_id){
            $mhs = Mahasiswa::find($mhs_id);
        }else{
            $mhs = new Mahasiswa;
        }
        
        // insert variable(data) to field(DB)
        $mhs->npm = $npm;
        $mhs->nama = $nama;
        $mhs->jenis_kelamin = $jenis_kelamin;
        $mhs->fakultas_id = $fakultas_id;
        $mhs->prodi_id = $prodi_id;
        $mhs->alamat = $alamat;
        $mhs->save();

        return back();
        // if($mhs->id = $mhs_id){
        //     return 'Ubah data berhasil !';
        // }else{
        //     return 'Simpan data berhasil !';
        // }
    }

    public function delete(Request $req)
    {
        $mhs_id = $req->mahasiswa_id;
        $mhs = Mahasiswa::find($mhs_id);
        $mhs->delete();
        return [
            'status' => 'success'
        ];
        //     if($mhs){
    //         $mhs->delete();
    //         return 'Hapus data berhasil !';
    //     }else{
    //         return 'Mahasiswa tidak ada pada data';
    //     }
    }
}