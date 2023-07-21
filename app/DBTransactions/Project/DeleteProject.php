<?php 
namespace App\DBTransactions\Project;

use App\Traits\ErrorTrait;
use App\Classes\DBTransaction;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

/**
 * Class DeleteProject
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class DeleteProject extends DBTransaction
{
    use ErrorTrait;
    private $id;

    /**
     * Assign id
     * @author Kyi Lin Lin Thant
     * @param $id
     * @create 30/06/2023
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    /**
     * Delete Project
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function process()
    {
        try {
            $id = request()->input('project_id');

            // check if the project ID is not associated with any employee
            $isNotAssigned = DB::table('employee_project')
                ->where('project_id', $id)
                ->doesntExist();

            if (!$isNotAssigned) {
                return ['status' => false];
            } else {
                Project::where('id', $id)->delete();
                return ['status' => true];
            } 
               
        } catch (\Exception $e) {
            return redirect()->back()->with('error');
        }
    }
}