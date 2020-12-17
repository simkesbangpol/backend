<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        $summary = ReportCategory::filter($request->all())->get();

        return response()->json(['success' => true, 'data' => $summary, 'message' => "Summary"]);
    }

    public function getReports($status){
        $reports = Report::where('status', $status)->orderByDesc('created_at')->paginate(15);
        return response()->json(['success' => true, 'data' => $reports, 'message' => "List of reports"]);
    }
}
