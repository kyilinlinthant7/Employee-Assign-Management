<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Exports\EmployeesPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExcelExport;
use Illuminate\Support\Facades\Redirect;

/**
 * Class DownloadController
 * @author Kyi Lin Lin Thant
 * @create 26/06/2023
 * @return array
 */
class DownloadController extends Controller
{
    private $employeeController;

    public function __construct(EmployeeController $employeeController)
    {
        $this->employeeController = $employeeController;
    }
    
    /**
     * Check the export data download type.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkDownloadType(Request $request) 
    {
        // accept data from inputs
        $downloadType = $request->input('download_option');
        
        if ($downloadType == 1){
            // go pdf download
            return $this->downloadPdf($request);
        } elseif ($downloadType == 2) {
            // go excel download
            return $this->downloadExcel($request);
        } else {
            // if user didn't choose option
            $errorMessage = "Please choose an option!";
            return view('error', compact('errorMessage'));
        }
    }

    /**
     * Download in PDF format.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    private function downloadPdf(Request $request) 
    {
        try {
            $searchInputs = json_decode($request->input('search_inputs'), true);
            $currentPage = $searchInputs['page'] ?? 1; // get the current page from the search inputs
            $employeesExportPdf = new EmployeesPdfExport($searchInputs, $currentPage);
        
            // generate and download the PDF file
            return $employeesExportPdf->download('employees.pdf');
        } catch (\Exception $e) {
            $errors = new MessageBag(['error' => 'An error occurred while exporting data. Please try again.']);
            return Redirect::back()->withErrors($errors);
        }
    }

    /**
     * Download in Excel format.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    private function downloadExcel(Request $request)
    {
        try {
            // decode to get the separated search hidden inputs in which storing JSON encoded data
            $searchInputs = json_decode($request->input('search_inputs'), true);

            $employeeId = $searchInputs['employee_id'] ?? null;
            $careerPart = $searchInputs['career_part'] ?? null;
            $level = $searchInputs['level'] ?? null;

            // filter data with search inputs using query
            $employees = Employee::query();

            if ($employeeId) {
                $employees->where('employee_id', 'LIKE', '%' . $employeeId . '%');
            }

            if ($careerPart) {
                $employees->where('career_part', $careerPart);
            }

            if ($level) {
                $employees->where('level', $level);
            }

            $employees->orderByDesc('id');

            $perPage = 20;
            $currentPage = $request->input('page', 1);

            if ($currentPage == 1) {
                $employees = $employees->paginate($perPage);
            } else {
                $employees = $employees->simplePaginate($perPage);
            }

            if ($employees->isEmpty()) {
                $errors = new MessageBag(['error' => 'There is no employee to export data.']);
                return Redirect::back()->withErrors($errors);
            }

            $export = new EmployeesExcelExport($employees, $currentPage);

            // generate Excel file
            $timestamp = now()->format('YmdHis');
            $filename = 'employees_' . $timestamp . '.xlsx';
            return Excel::download($export, $filename);
            
        } catch (\Exception $e) {
            $errors = new MessageBag(['error' => 'An error occurred while exporting data. Please try again.']);
            return Redirect::back()->withErrors($errors);
        }
    }
}