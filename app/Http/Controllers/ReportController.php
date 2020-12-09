<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(){
        $reports = DB::table('reports')->paginate(15);

        return response()->json(['status' => 'success', 'data' => $reports], 200);
    }

    public function store(CreateReportRequest $request){
        $report = new Report($request->all());
        if($report){
            return response()->json(['status' => 'success', 'data' => $report, 'message' => "Report submitted successfully"], 200);
        }
    }

    public function get($id){
        $report = Report::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Report with ID $id not found"], 404);
        }

        return response()->json(['status' => 'success', 'data' => $report, 'message' => "Report loaded successfully"], 200);
    }

    public function destroy($id){
        $report = Report::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Report with ID $id not found"], 404);
        }
        if($report->delete()){
            return response()->json(['status' => 'success', 'data' => '', 'message' => "Report deleted successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while deleting a report"], 400);
    }

    public function update(UpdateReportRequest $request, $id){
        $report = Report::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Report with ID $id not found"], 404);
        }

        if($report->update($request->all())){
            return response()->json(['status' => 'success', 'data' => $report, 'message' => "Report updated successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while updating a report"], 400);
    }
}
