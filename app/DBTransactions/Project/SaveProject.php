<?php

namespace App\DBTransactions\Project;

use App\Models\Project;
use App\Traits\ErrorTrait;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;

/**
 * Class SaveProject
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class SaveProject extends DBTransaction
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
     * Save Project
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function process()
    {
        try {
            DB::beginTransaction();
    
            $request = $this->request;
            $project = new Project();
    
            $project->name = $request->project_name_add;
            $project->created_by = session('login_id');
            $project->updated_by = session('login_id');
    
            $project->save();
    
            if (!$project) {
                DB::rollBack();
                return redirect()->back()->with('error', 'An error occurred while adding the project.');
            }
    
            DB::commit();
    
            return redirect()->back()->with('success');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error');
        }
    }
}