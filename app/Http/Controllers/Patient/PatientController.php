<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facility;
use App\Barangay;

class PatientController extends Controller
{
    public function patientList()
    {
        $data = array();

        return view('patient.patient',[
            'data' => $data
        ]);
    }

    public function patientUpdate(Request $req)
    {
       
        $user = Session::get('auth');

        $municity =  Facility::select(
            "facilities.*",
            "prov.prov_name as province",
            "prov.prov_psgc as p_id",
            "mun.muni_name as muncity",
            "mun.muni_psgc as m_id",
            "bar.brg_name as barangay",
            "bar.brg_psgc as b_id",
        ) ->leftJoin("provinces as prov","prov.prov_psgc","=","facilities.prov_psgc")
         ->leftJoin("municipal_cities as mun","mun.muni_psgc","=","facilities.muni_psgc")
         ->leftJoin("barangays as bar","bar.brg_psgc","=","facilities.brgy_psgc")
         ->where('facilities.id',$user->facility_id)
        ->get();

        return view('patient.patient_body',[
            'municity' => $municity
        ]);
    }

    public function getBaranggays($muncity_id)
    {
        $brgy = Barangay::where('muni_psgc',$muncity_id)
        ->orderBy('brg_name','asc')
        ->get();
        return $brgy;

    }
}
