<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportCategoryRequest;
use App\Http\Requests\UpdateReportCategoryRequest;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCategoryController extends Controller
{

    public function index(Request $request){
        $reports = ReportCategory::filter($request->all())->orderByDesc('created_at')->paginate(15);

        return response()->json(['status' => 'success', 'data' => $reports], 200);
    }

    public function store(Request $request){
        $report = new ReportCategory($request->all());
        if($report->save()){
            return response()->json(['status' => 'success', 'data' => $report, 'message' => "ReportCategory submitted successfully"], 200);
        }
    }

    public function get($id){
        $report = ReportCategory::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "ReportCategory with ID $id not found"], 404);
        }

        return response()->json(['status' => 'success', 'data' => $report, 'message' => "ReportCategory loaded successfully"], 200);
    }

    public function destroy($id){
        $report = ReportCategory::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "ReportCategory with ID $id not found"], 404);
        }
        if($report->delete()){
            return response()->json(['status' => 'success', 'data' => '', 'message' => "ReportCategory deleted successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while deleting a report"], 400);
    }

    public function update(Request $request, $id){
        $report = ReportCategory::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "ReportCategory with ID $id not found"], 404);
        }

        if($report->update($request->all())){
            return response()->json(['status' => 'success', 'data' => $report, 'message' => "ReportCategory updated successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while updating a report"], 400);
    }
}
