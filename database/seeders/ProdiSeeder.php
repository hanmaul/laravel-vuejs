<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getFTIK = Fakultas::where('nama','FTIK')->first();
        $FTIK_id = $getFTIK->id;

        $getFMIPA = Fakultas::where('nama','FMIPA')->first();
        $FMIPA_id = $getFMIPA->id;

        Prodi::insert([
            [
                'fakultas_id' => $FTIK_id,
                'nama' => 'Teknik Informatika'
            ], [
                'fakultas_id' => $FTIK_id,
                'nama' => 'Sistem Informasi'
            ], [
                'fakultas_id' => $FMIPA_id,
                'nama' => 'Sains'
            ], [
                'fakultas_id' => $FMIPA_id,
                'nama' => 'Matematika'
            ]
        ]);
    }
}
