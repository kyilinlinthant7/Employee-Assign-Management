<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Traits\ErrorTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Redirect;
use App\Interfaces\ProjectAssignmentInterface;
use App\Http\Requests\SaveProjectAssignmentRequest;
use App\DBTransactions\ProjectAssignment\SaveProjectAssignment;

/**
 * Class ProjectAssignmentController
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 * @return array
 */
class ProjectAssignmentController extends Controller
{
    use ErrorTrait;
    protected $projectAssignmentInterface;

    // create a new constructor for this controller
    public function __construct(ProjectAssignmentInterface $projectAssignmentInterface)
    {
        $this->projectAssignmentInterface = $projectAssignmentInterface;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $employees = Employee::all();
            // retrieve projects
            $projects = Project::all();
            $project = new Project(); 
            return view('project-assignments.create', compact('employees', 'projects', 'project'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load the create page.')->with('errorCode', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  SaveProjectAssignmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveProjectAssignmentRequest $request)
    {
        $newEmployeeAssignment = new SaveProjectAssignment($request);
        $result = $newEmployeeAssignment->process();

        if ($result['status']) {
            return redirect()->back()->with('success', 'Employee assignment registered successfully!');
        } else {
            return redirect()->back()->with('error', $result['error']);
        }
    }

    /**
     * Get the employee name by employee id.
     *
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getEmployeeName (Request $request) 
    {
        $employeeId = $request->input('employee_id');
        $employee = Employee::find($employeeId);
        // check employee exists
        if ($employee) {
            return response()->json(['name' => $employee->name]);
        }
        return response()->json(['name' => '']);
    }

    /**
     * Download project documents.
     *
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  $fileName
     * @return \Illuminate\Http\Response
     */
    public function downloadDocuments($fileName) 
    {
        $filePath = storage_path('/app/documentations/' . $fileName);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            $errors = new MessageBag(['error' => 'Sorry! Fail to download file.']);
            return redirect()->route('employees')->withErrors($errors);
        }
    }
}