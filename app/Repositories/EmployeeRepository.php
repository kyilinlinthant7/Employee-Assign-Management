<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\ProgrammingLanguage;
use App\Interfaces\EmployeeInterface;

/**
 * Manage database for Employee.
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class EmployeeRepository implements EmployeeInterface
{
    /**
     * Get all employees from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function getAllEmployees()
    {
        $employees = Employee::orderBy('id', 'desc')->paginate(20);
        return $employees;
    }

    /**
     * Get employee by a specific ID from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @param  $id
     * @return array
     */
    public function getEmployeeById($id)
    {
        $employee = Employee::find($id);
        return $employee;
    }

    /**
     * Get all programming languages from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function getAllProgrammingLanguages()
    {
        $programmingLanguages = ProgrammingLanguage::all();
        return $programmingLanguages;
    }

    /**
     * Filter employee by a specific ID from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @param  $employeeId
     * @return array
     */
    public function searchByEmployeeId($employeeId)
    {
        return Employee::where('employee_id', 'LIKE', '%'. $employeeId . '%')->get();
    }


    /**
     * Filter employees by a specific CareerPart from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @param  $careerPart
     * @return array
     */
    public function searchByCareerPart($careerPart)
    {
        return Employee::where('career_part', $careerPart)->get();
    }

    /**
     * Filter employees by a specific Level from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @param  $level
     * @return array
     */
    public function searchByLevel($level)
    {
        return Employee::where('level', $level)->get();
    }

    /**
     * Filter employees by a specific Employee ID, Career Part and Level from database.
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @param  $employeeId, $careerPart, $level
     * @return array
     */
    public function searchByCriteria($employeeId, $careerPart, $level)
    {
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

        return $query->get();
    }

    /**
     * Download Filtered employees by a specific Employee ID, Career Part and Level from database.
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  $employeeId, $careerPart, $level
     * @return array
     */
    public function downloadFilteredData($employeeId, $careerPart, $level)
    {
        $this->searchByCriteria($employeeId, $careerPart, $level);
    }

    /**
     * Get distinct career parts from database.
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @return array
     */
    public function getDistinctCareerParts() 
    {
        $distinctCareerParts = DB::table('employees')
        ->select('career_part')
        ->whereNull('deleted_at')
        ->distinct()
        ->pluck('career_part');

        return $distinctCareerParts;
    }

    /**
     * Get distinct levels from database.
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @return array
     */
    public function getDistinctLevels() 
    {
        $distinctLevels = DB::table('employees')
        ->select('level')
        ->whereNull('deleted_at')
        ->distinct()
        ->pluck('level');

        return $distinctLevels;
    }

    /**
     * Get detail data of a specific employee from database.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  $id
     * @return array
     */
    public function detailShow($id) 
    {
        $employeeProjects = DB::table('documentations')
        ->select('employee_project.employee_id', 'employee_project.project_id', 'projects.name', 'employee_project.start_date', 'employee_project.end_date', 'documentations.file_name', 'documentations.file_path')
        ->join('employee_project', 'employee_project.id', '=', 'documentations.employee_project_id')
        ->join('projects', 'projects.id', '=', 'employee_project.project_id')
        ->where('employee_project.employee_id', $id)
        ->get();

        return $employeeProjects;
    }
}