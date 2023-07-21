<?php

namespace App\Exports;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Concerns\FromView;

/**
 * Class EmployeesPdfExport
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class EmployeesPdfExport implements FromView
{
    private $searchInputs;
    private $currentPage;
    private $perPage;
    private $total;

    public function __construct($searchInputs, $currentPage = 1) 
    {
        $this->searchInputs = $searchInputs;
        $this->currentPage = $currentPage;
        $this->perPage = 20;
        $this->total = 0;
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $query = $this->getFilteredEmployees();

        $this->total = $query->count();

        // calculate the starting index for the current page
        $startIndex = ($this->currentPage - 1) * $this->perPage;

        // retrieve the data for the current page
        $employees = $query
            ->skip($startIndex)
            ->take($this->perPage)
            ->get();

        // pass the filtered employees and current page to the PDF view
        return view('employees', [
            'employees' => $employees,
            'currentPage' => $this->currentPage,
        ]);
    }


    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function download($filename = 'employees.pdf')
    {
        $timestamp = now()->format('YmdHis');
        $filename = 'employees_' . $timestamp . '.pdf';

        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');

        $query = $this->getFilteredEmployees();

        $startIndex = ($this->currentPage - 1) * $this->perPage;

        $employees = $query
            ->skip($startIndex)
            ->take($this->perPage)
            ->get();


        if ($employees->isEmpty()) {
            $errors = new MessageBag(['error' => 'There is no employee to export data.']);
            return Redirect::back()->withErrors($errors);
        }

        $html = $this->generatePdfHtml($employees, $this->currentPage);
    
        // load the HTML into Dompdf
        $dompdf->loadHtml($html);
        $dompdf->render();
    
        // generate the output and download the PDF
        $output = $dompdf->output();
    
        return response($output)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    private function getFilteredEmployees()
    {
        $employeeId = $this->searchInputs['employee_id'] ?? null;
        $careerPart = $this->searchInputs['career_part'] ?? null;
        $level = $this->searchInputs['level'] ?? null;

        $query = Employee::query();

        if ($employeeId) {
            $query->where('employee_id', 'LIKE', '%' . $employeeId . '%');
        }

        if ($careerPart) {
            $query->where('career_part', $careerPart);
        }

        if ($level) {
            $query->where('level', $level);
        }
        
        $query->orderByDesc('id');
        return $query;
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    private function generatePdfHtml($employees, $currentPage)
    {
        $html = '<table>';

        // generate the main title
        $html .= '<tr>';
        $html .= '<th colspan="6" style="text-align: center; font-size: 24px; color: #5b63bd;">Employee List (Page ' . $currentPage . ')</th>';
        $html .= '</tr>';
        
        // generate the table titles
        $html .= '<tr style="background-color: #7393B3; font-size: 14px;">';
        $html .= '<th style="color: #FFFFFF;">Employee ID</th>';
        $html .= '<th style="color: #FFFFFF;">Name</th>';
        $html .= '<th style="color: #FFFFFF;">Email</th>';
        $html .= '<th style="color: #FFFFFF;">Date of Birth</th>';
        $html .= '<th style="color: #FFFFFF;">Career Part</th>';
        $html .= '<th style="color: #FFFFFF;">Level</th>';
        $html .= '<th style="color: #FFFFFF;">Phone</th>';
        $html .= '</tr>';

        // generate the table rows with data
        foreach ($employees as $employee) {
            $html .= '<tr style="text-align: center; font-size: 13px; border: 1px;">';
            $html .= '<td>' . $employee->employee_id . '</td>';
            $html .= '<td>' . $employee->name . '</td>';
            $html .= '<td>' . $employee->email . '</td>';
            $html .= '<td>' . Carbon::parse($employee->date_of_birth)->format('d-m-Y') . '</td>';
            // replace custom values based on career part
            $careerPart = $employee->career_part;
            if ($careerPart == 1) {
                $careerPartName = 'Front-end Developer';
            } elseif ($careerPart == 2) {
                $careerPartName = 'Back-end Developer';
            } elseif ($careerPart == 3) {
                $careerPartName = 'Full-stack Developer';
            } else {
                $careerPartName = 'Mobile Developer';
            }
            $html .= '<td>' . $careerPartName . '</td>';
            // replace custom values based on level
            $level = $employee->level;
            if ($level == 1) {
                $levelName = 'Beginner';
            } elseif ($level == 2) {
                $levelName = 'Junior Engineer';
            } elseif ($level == 3) {
                $levelName = 'Engineer';
            } else {
                $levelName = 'Senior Engineer';
            }
            $html .= '<td>' . $levelName . '</td>';
            $html .= '<td style="padding-left: 10px; padding-right: 10px;">' . $employee->phone . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }
}