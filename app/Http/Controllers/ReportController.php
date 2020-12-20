<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ReportController extends Controller
{
    public function index(Request $request){
        $perPage = $request->has('perPage') ? $request->get('perPage') : 15;
        $reports = Report::filter($request->all())->with('category')->orderByDesc('id')->paginate($perPage);

        return response()->json(['status' => 'success', 'data' => $reports], 200);
    }

    public function store(Request $request){
        $report = new Report($request->except('file'));
        if($report->save()){
            return response()->json(['status' => 'success', 'data' => $report, 'message' => "Report submitted successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while submitting a report"], 400);
    }

    public function get($id){
        $report = Report::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Report with ID $id not found"], 404);
        }
        if(app('auth')->user()->id !== $report->user_id)
            throw new UnauthorizedException(403);

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

    public function update(Request $request, $id){
        $report = Report::find($id);
        if(!$report){
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "Report with ID $id not found"], 404);
        }

        if($report->update($request->all())){
            return response()->json(['status' => 'success', 'data' => $report, 'message' => "Report updated successfully"], 200);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Error while updating a report"], 400);
    }

    public function fileUpload(Request $request, $id){
        $report = Report::find($id);
        if ($report) {
            if ($request->hasFile('file')) {
                $original_filename = $request->file('file')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/user/';
                $uploadedFile = 'U-' . app('auth')->user()->id . '-' . time() . '.' . $file_ext;

                if ($request->file('file')->move($destination_path, $uploadedFile)) {
                    $report->file = '/upload/user/' . $uploadedFile;
                    $report->save();
                    return response()->json(['status' => 'success', 'data' => $report, 'message' => "File successfully uploaded"]);
                } else {
                    return response()->json(['status' => 'failed', 'data' => [], 'message' => "Error while uploading the file"], 400);
                }
            }
            return response()->json(['status' => 'failed', 'data' => '', 'message' => "No file attached"], 400);
        }
        return response()->json(['status' => 'failed', 'data' => '', 'message' => "Report with ID $id not found"], 404);
    }

    public function export(Request $request){
        $reports = Report::filter($request->all())->with('category')->orderByDesc('id')->get();

        if(sizeof($reports) > 0){
            $spreadsheet = new Spreadsheet();
            $excel_writer = new Xlsx($spreadsheet);

            $spreadsheet->setActiveSheetIndex(0);
            $activeSheet = $spreadsheet->getActiveSheet();

            $activeSheet->setCellValue('A1', 'Judul');
            $activeSheet->setCellValue('B1', 'Fakta');
            $activeSheet->setCellValue('C1', 'Tanggal');
            $activeSheet->setCellValue('D1', 'Lokasi');
            $activeSheet->setCellValue('E1', 'Deskripsi');
            $activeSheet->setCellValue('F1', 'Aksi');
            $activeSheet->setCellValue('G1', 'Rekomendasi');
            $activeSheet->setCellValue('H1', 'Status');
            $activeSheet->setCellValue('I1', 'Kategori');
            $activeSheet->setCellValue('J1', 'Kecamatan');
            $activeSheet->setCellValue('K1', 'Kelurahan');
            $activeSheet->setCellValue('L1', 'Dilaporkan oleh');

            foreach($reports as $index => $report){
                $activeSheet->setCellValue('A'.($index+2), $report->title);
                $activeSheet->setCellValue('B'.($index+2), $report->fact);
                $activeSheet->setCellValue('C'.($index+2), $report->parsed_date);
                $activeSheet->setCellValue('D'.($index+2), $report->location);
                $activeSheet->setCellValue('E'.($index+2), $report->description);
                $activeSheet->setCellValue('F'.($index+2), $report->action);
                $activeSheet->setCellValue('G'.($index+2), $report->recommendation);
                $activeSheet->setCellValue('H'.($index+2), $report->parsed_status);
                $activeSheet->setCellValue('I'.($index+2), $report->category()->first()->name);
                $activeSheet->setCellValue('J'.($index+2), $report->village()->first()->district()->first()->name);
                $activeSheet->setCellValue('K'.($index+2), $report->village()->first()->name);
                $activeSheet->setCellValue('L'.($index+2), $report->user()->first()->name);
            }
        }
        $file_ext = "xlsx";
        $fileName = 'EXPORT-' . time() . '.' . $file_ext;

        try {
            $excel_writer->save($fileName);
            return response()->download($fileName, 'reports.xlsx', [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return response()->json(['success' => 'failed', 'message' => "Error exporting data"], 400);
        }

    }

    public function import(Request $request){
        $user = app('auth')->user();

        if ($request->hasFile('file')) {
            $original_filename = $request->file('file')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './upload/import/';
            $uploadedFile = 'U-' . $user->id . '-' . time() . '-import.' . $file_ext;

            if ($request->file('file')->move($destination_path, $uploadedFile)) {
                $inputFileType = ucfirst($file_ext);
                $inputFileName = $destination_path.DIRECTORY_SEPARATOR.$uploadedFile;

                $reader = IOFactory::createReader($inputFileType);
                $reader->setLoadSheetsOnly(["Format Import Laporan"]);
                $spreadsheet = $reader->load($inputFileName);

                $sheet = $spreadsheet->getSheetByName("Format Import Laporan");


                $highestRow = $sheet->getHighestRow(); // e.g. 10
                $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); // e.g. 5

                for ($row = 2; $row <= $highestRow; ++$row) {
                    Report::create([
                        'title' => $sheet->getCellByColumnAndRow(1, $row)->getValue(),
                        'fact' => $sheet->getCellByColumnAndRow(3, $row)->getValue(),
                        'date' => $sheet->getCellByColumnAndRow(4, $row)->getValue(),
                        'location' => $sheet->getCellByColumnAndRow(5, $row)->getValue(),
                        'description' => $sheet->getCellByColumnAndRow(8, $row)->getValue(),
                        'action' => $sheet->getCellByColumnAndRow(9, $row)->getValue(),
                        'recommendation' => $sheet->getCellByColumnAndRow(10, $row)->getValue(),
                        'village_id' => $sheet->getCellByColumnAndRow(12, $row)->getValue(),
                        'category_id' => $sheet->getCellByColumnAndRow(13, $row)->getValue(),
                        'user_id' => $user->id
                    ]);
                }

                return response()->json(['status' => 'success', 'message' => "File successfully uploaded"]);
            } else {
                return response()->json(['status' => 'failed', 'message' => "Error while uploading the file"], 400);
            }
        }
    }
}
