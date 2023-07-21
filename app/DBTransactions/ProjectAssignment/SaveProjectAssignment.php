<?php 
namespace App\DBTransactions\ProjectAssignment;

use App\Traits\ErrorTrait;
use App\Classes\DBTransaction;
use App\Models\Documentation;
use App\Models\EmployeeProject;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SaveProjectAssignment
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class SaveProjectAssignment extends DBTransaction
{
    use ErrorTrait;

    private $request;

    /**
     * Assign Request
     * @author Kyi Lin Lin Thant
     * @param $request
     * @create 30/06/2023
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Save Project Assignment
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function process()
    {
        try {
            // set request
            $request = $this->request;

            // create new EmployeeProject instance
            $employeeProject = new EmployeeProject();

            // set employee project data
            $employeeProject->employee_id = $request->employee_id;
            $employeeProject->start_date = $request->start_date;
            $employeeProject->end_date = $request->end_date;

            // get project id from projects
            $projectName = $request->project_name;
            $project = DB::table('projects')
                ->where('name', $projectName)
                ->first();
            $projectId = $project->id;

            // insert project id into pivot table
            if ($project) {
                $employeeProject->project_id = $projectId;
            }

            $employeeProject->created_by = session('login_id');
            $employeeProject->updated_by = session('login_id');

            // save employee project assignment
            if ($employeeProject->save()) {
                // save documentations
                if ($request->hasFile('files')) {
                    $files = $request->file('files');
                    foreach ($files as $file) {
                        // store each uploaded file in the storage/app/public/uploads directory
                        $timestamp = Carbon::now()->format('YmdHis');
                        $filename = $file->getClientOriginalName() . '_' . $timestamp;
                        $file->storeAs('documentations', $filename);

                        // create a new document record
                        $document = new Documentation();
                        $document->employee_project_id = $employeeProject->id;
                        $document->file_name = $filename;
                        $document->file_size = $file->getSize();
                        $document->file_path = 'documentations/' . $filename;
                        $document->created_by = session('login_id');
                        $document->updated_by = session('login_id');
                        $document->save();
                    }
                }
                return ['status' => true, 'message' => 'Employee assignment registered successfully!'];
            } 

        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()]; 
        }
    }
}