<?php
namespace App\Logic;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Interfaces\EmployeeInterface;

class EmployeeData
{
    public static function generateData(Request $request, EmployeeInterface $employeeInterface, $currentPage)
    {
        $searchInputs = [
            'employee_id' => $request->input('employee_id'),
            'career_part' => $request->input('career_part'),
            'level' => $request->input('level'),
            'page' => $currentPage,
        ];

        $distinctCareerParts = $employeeInterface->getDistinctCareerParts();
        $distinctLevels = $employeeInterface->getDistinctLevels();

        $query = Employee::query();

        if ($searchInputs['employee_id']) {
            $query->where('employee_id', 'LIKE', '%' . $searchInputs['employee_id'] . '%');
        }

        if ($searchInputs['career_part']) {
            $query->where('career_part', $searchInputs['career_part']);
        }

        if ($searchInputs['level']) {
            $query->where('level', $searchInputs['level']);
        }

        $query->orderByDesc('id');

        $perPage = 20;
        $page = $currentPage ?: 1;

        $employees = $query->paginate($perPage, ['*'], 'page', $page)->appends($searchInputs);
        $searchInputs['page'] = $currentPage;
        session(['currentPage' => $currentPage]);

        if ($employees->currentPage() > $employees->lastPage() ) {
            $page = $employees->lastPage();
            $employees = $query->paginate($perPage, ['*'], 'page', $page)->appends($searchInputs);
        }

        return compact('employees', 'searchInputs', 'distinctCareerParts', 'distinctLevels');
    }

    public static function getSearchData(Request $request, EmployeeInterface $employeeInterface)
    {
        $employeeId = $request->input('employee_id');
        $careerPart = $request->input('career_part');
        $level = $request->input('level');
        $currentPage = $request->input('page') ?? 1;

        // retrieve distinct career parts and levels
        $distinctCareerParts = $employeeInterface->getDistinctCareerParts();
        $distinctLevels = $employeeInterface->getDistinctLevels();

        // query employees based on the search inputs
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
        $perPage = 20;
        $employees = $query->paginate($perPage);

        // check if the current page exceeds the last page
        if ($employees->currentPage() > $employees->lastPage()) {
            $currentPage = $employees->lastPage();
            $employees = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }

        // to pass the search inputs to the view
        $searchInputs = [
            'employee_id' => $employeeId,
            'career_part' => $careerPart,
            'level' => $level,
            'page' => $currentPage,
        ];

        // to get values after going to next paginations
        $employees->appends($searchInputs);

        // store the current page in the session
        session(['currentPage' => $currentPage]);

        return compact('employees', 'searchInputs', 'distinctCareerParts', 'distinctLevels');
    }
}