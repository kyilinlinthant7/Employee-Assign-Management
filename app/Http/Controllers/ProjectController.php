<?php

namespace App\Http\Controllers;

use App\Traits\ErrorTrait;
use App\Interfaces\ProjectInterface;
use App\Http\Requests\SaveProjectRequest;
use App\DBTransactions\Project\SaveProject;
use App\DBTransactions\Project\DeleteProject;

/**
 * Class ProjectController
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 * @return array
 */
class ProjectController extends Controller
{
    use ErrorTrait;
    protected $projectInterface;
    
    // create a new constructor for this controller
    public function __construct(ProjectInterface $projectInterface)
    {
        $this->projectInterface = $projectInterface;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  SaveProjectRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveProjectRequest $request)
    {
        $newProject = new SaveProject($request);
        $result = $newProject->process();
        if ($result->isRedirect()) {
            return $result->with('success', 'A new project was added successfully!');
        } else {
            return redirect()->back()->with('error', 'An error occurred while adding the project.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = $this->projectInterface->getProjectById($id);
        if (!$project) {
            return $this->error('error', 500);
        }

        $delete = new DeleteProject($id);
        $result = $delete->executeProcess();

        if ($result) {
            return redirect()->back()->with('success', 'Project deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Sorry! This project is assigned by employees.');
        }
    }
}