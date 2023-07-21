<?php 
namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Traits\ErrorTrait;
use App\Models\Documentation;
use App\Classes\DBTransaction;
use App\Models\EmployeeProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class DeleteEmployee
 * @author Kyi Lin Lin Thant
 * @create 28/06/2023
 */
class DeleteEmployee extends DBTransaction
{
    use ErrorTrait;
    private $id;

    /**
     * Assign id
     * @author Kyi Lin Lin Thant
     * @param $id
     * @create 28/06/2023
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    /**
     * Delete Employee
     * @author Kyi Lin Lin Thant
     * @create 28/06/2023
     * @return array
     */
    public function process()
    {
        try {
            $id = $this->id;

            // retrieve the employee-projects associated with the employee
            $employeeProjects = EmployeeProject::where('employee_id', $id)->get();

            foreach ($employeeProjects as $employeeProject) {
                $documentations = Documentation::where('employee_project_id', $employeeProject->id)->get();
            
                foreach ($documentations as $documentation) {
                    $documentation->delete();

                    $filePath = storage_path('app/documentations/' . $documentation->file_name);
                    if (File::exists($filePath)) {
                        File::chmod($filePath, 0644);
                        File::delete($filePath);
                    }
                }
            }
            

            EmployeeProject::where('employee_id', $id)->delete();

            // delete the employee
            $delete = Employee::where('id', $id)->delete();

            if (!$delete) {
                DB::rollBack();
                return ['status' => false, 'error' => 'Failed to delete employee!'];
            } else {
                DB::commit();
                return ['status' => true, 'error' => null];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error');
        }       
    }
}