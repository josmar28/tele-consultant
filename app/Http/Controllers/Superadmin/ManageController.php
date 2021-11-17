<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Barangay;
use App\Facility;
use App\MunicipalCity;
use App\Province;
use App\User;
class ManageController extends Controller
{
    public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }
	//Start User Module
    public function indexUser(Request $request) {
    	$users = User::all();
        $keyword = $request->search;
        $data = new User();
        if($keyword){
            $data = $data
                ->where(function($q) use($keyword){
                $q->where('fname','like',"%$keyword%")
                    ->orwhere('mname','like',"%$keyword%")
                    ->orwhere('lname','like',"%$keyword%")
                    ->orwhere('username','like',"%$keyword%")
                    ->orwhere(\DB::raw('concat(fname," ",lname)'),'like',"$keyword")
                    ->orwhere(\DB::raw('concat(lname," ",fname)'),'like',"$keyword");
            });
        }

        if($request->facility_filter)
            $data = $data->where("facility_id",$request->facility_filter);

        $data = $data
                ->where(function($q){
                    $q->where("level",'admin')
                    ->orWhere("level","doctor")
                        ->orWhere("level","patient");
                    })
                ->orderBy('lname','asc')
                ->paginate(20);

        $facility = Facility::orderBy('facilityname','asc')->get();
        return view('superadmin.users',[
            'title' => 'List of Support User',
            'data' => $data,
            'facility' => $facility,
            'search' => $keyword,
            'facility_filter' => $request->facility_filter,
            'users' => $users,
        ]);
    }

    public function deactivateUser($id) {
        $user = User::find($id);
        $is = $user->status == 'deactivate' ? 'active' : 'deactivate';
        $msg = $user->status == 'deactivate' ? 'Successfully activate account' : 'Successfully deactivate account';
        $act = $user->status == 'deactivate' ? 'action_made' : 'deactivate';
        $data = array(
            'status' => $is
        );
        $user->update($data);
        Session::put($act, $msg);

    }
    public function storeUser(Request $req) {
    	$facility = Facility::find($req->facility_id);
        $data = array(
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'level' => $req->level,
            'facility_id' => $req->facility_id,
            'status' => 'active',
            'contact' => $req->contact,
            'email' => $req->email,
            'designation' => $req->designation,
            'username' => $req->username,
            'password' => bcrypt($req->password)
        );
        if($req->user_id){
            Session::put("action_made","Successfully updated account");
            User::find($req->user_id)->update($data);
        }
        else{
            Session::put("action_made","Successfully added new account");
            User::create($data);
        }
    }
    // End User module

    //Start Facility Module
    public function indexFacility(Request $request) {
    	if($request->view_all == 'view_all')
            $keyword = '';
        else{
            if(Session::get("keyword")){
                if(!empty($request->keyword) && Session::get("keyword") != $request->keyword)
                    $keyword = $request->keyword;
                else
                    $keyword = Session::get("keyword");
            } else {
                $keyword = $request->keyword;
            }
        }

        Session::put('keyword',$keyword);

        $data = Facility::select(
            "facilities.*",
            "prov.prov_name as province",
            "mun.muni_name as muncity",
            "bar.brg_name as barangay",
        ) ->leftJoin("provinces as prov","prov.prov_psgc","=","facilities.prov_psgc")
         ->leftJoin("municipal_cities as mun","mun.muni_psgc","=","facilities.muni_psgc")
         ->leftJoin("barangays as bar","bar.brg_psgc","=","facilities.brgy_psgc");

        $data = $data->where('facilities.facilityname',"like","%$keyword%");

        $facilities = $data->get();
        $data = $data->orderBy('facilityname','asc')
            ->paginate(20);
        $province = Province::all();
        return view('superadmin.facility',[
            'title' => 'List of Facility',
            'data' => $data,
            'province' => $province,
            'facilities' => $facilities
        ]);
    }

    public function getMunandBrgy($id, $type) {
        if($type === 'municipality') {
            $municipal = MunicipalCity::where('prov_psgc', '=', $id)->orderBy('muni_name', 'asc')->get();
            return response()->json(['municipal'=>$municipal]);
        } else if($type === 'barangay'){
            $barangay = Barangay::where('muni_psgc', '=', $id)->orderBy('brg_name', 'asc')->get();
            return response()->json(['barangay'=>$barangay]);
        }
    }

    public function storeFacility(Request $req) {
        $facility = Facility::find($req->facility_id);
        $data = array(
            'fshortcode' => $req->fshortcode,
            'facilityname' => $req->facilityname,
            'oldfacilityname' => $req->oldfacilityname,
            'prov_psgc' => $req->prov_psgc,
            'muni_psgc' => $req->muni_psgc,
            'brgy_psgc' => $req->brgy_psgc,
            'streetname' => $req->streetname,
            'landlineno' => $req->landlineno,
            'faxnumber' => $req->faxnumber,
            'emailaddress' => $req->emailaddress,
            'officialwebsiteurl' => $req->officialwebsiteurl,
            'facilityhead_fname' => $req->facilityhead_fname,
            'facilityhead_lname' => $req->facilityhead_lname,
            'facilityhead_mi' => $req->facilityhead_mi,
            'facilityhead_position' => $req->facilityhead_position,
            'status' => $req->status,
            'hosp_licensestatus' => $req->hosp_licensestatus,
            'hosp_servcapability' => $req->hosp_servcapability,
            'hosp_bedcapacity' => $req->hosp_bedcapacity,
            'latitude' => $req->latitude,
            'longitude' => $req->longitude,
            'remarks' => $req->remarks
        );
        if(!$req->facility_id){
            Session::put("action_made","Successfully added new facility");
            Facility::create($data);
        }
        else{
            Session::put("action_made","Successfully updated facility");
            Facility::find($req->facility_id)->update($data);
        }

    }

    public function deleteFacility($id) {
        $facility = Facility::find($id);
        $facility->delete();
        Session::put("delete_action","Successfully delete facility");
    }
    // End Facility

    // Start Province Module
    public function indexProvince(Request $request) {
        if($request->view_all == 'view_all')
            $keyword = '';
        else{
            if(Session::get("keyword")){
                if(!empty($request->keyword) && Session::get("keyword") != $request->keyword)
                    $keyword = $request->keyword;
                else
                    $keyword = Session::get("keyword");
            } else {
                $keyword = $request->keyword;
            }
        }

        Session::put('keyword',$keyword);
        $provinces = Province::all();
        $data = Province::where('prov_name',"like","%$keyword%")
            ->orderBy("prov_name","asc")
            ->paginate(20);
        return view('superadmin.provinces',[
            'title' => 'List of Province',
            'provinces' => $provinces,
            'data' => $data
        ]);
    }

    public function storeProvince(Request $req) {
        $province = Province::find($req->province_id);
        $data = array(
            'prov_psgc' => $req->prov_psgc,
            'prov_name' => $req->prov_name,
            'reg_psgc' => '120000000'
        );
        if(!$req->province_id){
            Session::put("action_made","Successfully added new province");
            Province::create($data);
        }
        else{
            Session::put("action_made","Successfully updated province");
            Province::find($req->province_id)->update($data);
        }
    }

    public function deleteProvince($id) {
        $province = Province::find($id);
        $province->delete();
        Session::put("delete_action","Successfully delete province");
    }

    public function viewMunicipality(Request $request, $province_id, $province_name) {
        if($request->view_all == 'view_all')
            $keyword = '';
        else{
            if(Session::get("keyword_muncity")){
                if(!empty($request->keyword_muncity) && Session::get("keyword_muncity") != $request->keyword_muncity)
                    $keyword = $request->keyword_muncity;
                else
                    $keyword = Session::get("keyword_muncity");
            } else {
                $keyword = $request->keyword_muncity;
            }
        }

        Session::put('keyword_muncity',$keyword);
        $municipality = MunicipalCity::where('prov_psgc', '=', $province_id)->get();
        $data = MunicipalCity::where('muni_name',"like","%$keyword%")
            ->where("prov_psgc",$province_id)
            ->orderBy("muni_name","asc")
            ->paginate(20);

        return view('superadmin.municipality',[
            'title' => 'List of Municipality',
            'province_name' => $province_name,
            'province_id' => $province_id,
            'municipalities' => $municipality,
            'data' => $data
        ]);
    }

    public function viewBarangay(Request $request, $province_id, $province_name, $muni_id, $muni_name) {
        if($request->view_all == 'view_all')
            $keyword = '';
        else{
            if(Session::get("keyword_barangay")){
                if(!empty($request->keyword_barangay) && Session::get("keyword_barangay") != $request->keyword_barangay)
                    $keyword = $request->keyword_barangay;
                else
                    $keyword = Session::get("keyword_barangay");
            } else {
                $keyword = $request->keyword_barangay;
            }
        }

        Session::put('keyword_barangay',$keyword);
        $barangays = Barangay::where('prov_psgc', '=', $province_id)
                    ->where('muni_psgc', '=', $muni_id)
                    ->get();
        $data = Barangay::where('brg_name',"like","%$keyword%")
            ->where("prov_psgc",$province_id)
            ->where("muni_psgc",$muni_id)
            ->orderBy("brg_name","asc")
            ->paginate(20);

        return view('superadmin.barangays',[
            'title' => 'List of Barangay',
            'province_name' => $province_name,
            'province_id' => $province_id,
            'muncity_name' => $muni_name,
            'muncity_id' => $muni_id,
            'barangays' => $barangays,
            'data' => $data
        ]);
    }

    public function storeMunicipality(Request $req) {
        $municipality = MunicipalCity::find($req->muni_id);
        $data = array(
            'muni_psgc' => $req->muni_psgc,
            'muni_name' => $req->muni_name,
            'muni_void' => '0',
            'prov_psgc' => $req->prov_psgc,
            'zipcode' => $req->zipcode,
            'districtid' => '0',
            'reg_psgc' => '120000000'
        );
        if(!$req->muni_id){
            Session::put("action_made","Successfully added new municipality");
            MunicipalCity::create($data);
        }
        else{
            Session::put("action_made","Successfully updated municipality");
            MunicipalCity::find($req->muni_id)->update($data);
        }
    }

    public function deleteMunicipality($id) {
        $muni = MunicipalCity::find($id);
        $muni->delete();
        Session::put("delete_action","Successfully delete Municipality");
    }

    public function storeBarangay(Request $req) {
        $barangay = Barangay::find($req->brgy_id);
        $data = array(
            'brg_psgc' => $req->brg_psgc,
            'brg_name' => $req->brg_name,
            'brg_void' => '0',
            'prov_psgc' => $req->prov_psgc,
            'muni_psgc' => $req->muni_psgc,
            'reg_psgc' => '120000000'
        );
        if(!$req->brgy_id){
            Session::put("action_made","Successfully added new barangay");
            Barangay::create($data);
        }
        else{
            Session::put("action_made","Successfully updated barangay");
            Barangay::find($req->brgy_id)->update($data);
        }
    }

    public function deleteBarangay($id) {
        $brgy = Barangay::find($id);
        $brgy->delete();
        Session::put("delete_action","Successfully delete Barangay");
    }

}
