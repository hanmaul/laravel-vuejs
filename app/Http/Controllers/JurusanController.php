<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Prodi;

class JurusanController extends Controller
{
    public function getFakultas()
    {
        $data = Fakultas::all();
        return $data;
    }

    public function getProdi(Request $req)
    {
        $fakultas_id = $req->fakultas_id;
        $data = Prodi::where('fakultas_id',$fakultas_id)->get();
        return $data;
    }
}
