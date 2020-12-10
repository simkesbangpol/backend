<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VillageController extends Controller
{

    public function index(){
        $villages = DB::table('villages')->paginate(15);

        return response()->json(['status' => 'success', 'data' => $villages], 200);
    }

    public function store(Request $request){
        $village = new Village($request->all());
        if($village->save()){
            return response()->json(['status' => 'success', 'data' => $village, 'message' => "Village submitted successfully"], 200);
        }
    }

    public function get($id){
        $village = Village::find($id);
        if(!$village){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Village with ID $id not found"], 404);
        }

        return response()->json(['status' => 'success', 'data' => $village, 'message' => "Village loaded successfully"], 200);
    }

    public function destroy($id){
        $village = Village::find($id);
        if(!$village){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Village with ID $id not found"], 404);
        }
        if($village->delete()){
            return response()->json(['status' => 'success', 'data' => '', 'message' => "Village deleted successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while deleting a report"], 400);
    }

    public function update(Request $request, $id){
        $village = Village::find($id);
        if(!$village){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Village with ID $id not found"], 404);
        }

        if($village->update($request->all())){
            return response()->json(['status' => 'success', 'data' => $village, 'message' => "Village updated successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while updating a report"], 400);
    }
}
