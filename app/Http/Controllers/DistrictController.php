<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{

    public function index(){
        $districts = DB::table('districts')->paginate(30);

        return response()->json(['status' => 'success', 'data' => $districts], 200);
    }

    public function store(Request $request){
        $district = new District($request->all());
        if($district->save()){
            return response()->json(['status' => 'success', 'data' => $district, 'message' => "District submitted successfully"], 200);
        }
    }

    public function get($id){
        $district = District::find($id);
        if(!$district){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "District with ID $id not found"], 404);
        }

        return response()->json(['status' => 'success', 'data' => $district, 'message' => "District loaded successfully"], 200);
    }

    public function getVillages($id){
        $district = District::find($id);
        if(!$district){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "District with ID $id not found"], 404);
        }

        return response()->json(['status' => 'success', 'data' => $district->villages()->get(), 'message' => "District loaded successfully"], 200);
    }

    public function destroy($id){
        $district = District::find($id);
        if(!$district){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "District with ID $id not found"], 404);
        }
        if($district->delete()){
            return response()->json(['status' => 'success', 'data' => '', 'message' => "District deleted successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while deleting a report"], 400);
    }

    public function update(Request $request, $id){
        $district = District::find($id);
        if(!$district){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "District with ID $id not found"], 404);
        }

        if($district->update($request->all())){
            return response()->json(['status' => 'success', 'data' => $district, 'message' => "District updated successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while updating a report"], 400);
    }
}
