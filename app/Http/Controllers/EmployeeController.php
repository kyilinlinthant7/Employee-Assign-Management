<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Traits\ErrorTrait;
use App\Logic\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Models\ProgrammingLanguage;
use App\Interfaces\EmployeeInterface;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SaveEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\DBTransactions\Employee\SaveEmployee;
use App\DBTransactions\Employee\DeleteEmployee;
use App\DBTransactions\Employee\UpdateEmployee;

/**
 * Class EmployeeController
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 * @return array
 */
class EmployeeController extends Controller
{
    use ErrorTrait;
    protected $employeeInterface;
    
    public function __construct(EmployeeInterface $employeeInterface)
    {
        $this->employeeInterface = $employeeInterface;
    }

    public function searchEmployees(Request $request)
    {
        try {
            return view('employees.index', EmployeeData::getSearchData($request, $this->employeeInterface));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load the index page.');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $currentPage = $request->input('page', 1);
            $employeeData = EmployeeData::generateData($request, $this->employeeInterface, $currentPage);
            
            return view('employees.index', $employeeData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load the index page.')->with('errorCode', 500);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $latestEmployee = Employee::withTrashed()->latest('id')->first();
            $nextEmployeeId = $latestEmployee ? $latestEmployee->id + 1 : 1;
            $formattedEmployeeId = str_pad($nextEmployeeId, 5, '0', STR_PAD_LEFT);
            
            $programmingLanguages = ProgrammingLanguage::all();
            return view('employees.create', compact('formattedEmployeeId', 'programmingLanguages'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load the create page.')->with('errorCode', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @param  SaveEmployeeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveEmployeeRequest $request)
    {
        $newEmployee = new SaveEmployee($request);
        $result = $newEmployee->process();
        $currentPage = $request->input('page', 1);

        EmployeeData::generateData($request, $this->employeeInterface, $currentPage);

        $flashMessage = $result['status'] ? ['success' => 'Employee registered successfully.'] : ['error' => $result['error']];
        return redirect($result['status'] ? url('/employees') : back())->with($flashMessage);
    }

    /**
     * Display the specified resource.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $employee = $this->employeeInterface->getEmployeeById($id);
            $programmingLanguages = $this->employeeInterface->getAllProgrammingLanguages();
            $employeeProjects = $this->employeeInterface->detailShow($id);
            $selectedLanguages = explode(',', $employee->language);
            $selectedProgrammingLanguages = $employee->programmingLanguages->pluck('id')->toArray();
        
            return view('employees.show', compact('employee', 'programmingLanguages', 'selectedLanguages', 'selectedProgrammingLanguages', 'employeeProjects'));
        } catch (\Exception $e) {
            $errors = new MessageBag(['error' => 'Sorry! Failed to load the detail page.']);
            return redirect()->route('employees')->withErrors($errors);
        }        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $employee = $this->employeeInterface->getEmployeeById($id);
            $programmingLanguages = $this->employeeInterface->getAllProgrammingLanguages();
            
            $selectedLanguages = explode(',', $employee->language); // get from column
            $selectedProgrammingLanguages = $employee->programmingLanguages->pluck('id')->toArray(); // get from separated table
            
            return view('employees.edit', compact('employee', 'programmingLanguages', 'selectedLanguages', 'selectedProgrammingLanguages'));
        } catch (\Exception $e) {
            $errors = new MessageBag(['error' => 'Sorry! Failed to load the edit page.']);
            return redirect()->route('employees')->withErrors($errors);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  UpdateEmployeeRequest $request, $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeInterface->getEmployeeById($id);
        if (!$employee) {
            $errors = new MessageBag(['error' => 'Sorry! Unable to update this employee.']);
            return redirect()->route('employees')->withErrors($errors);
        }

        $currentPage = $request->input('page', session('currentPage', 1));

        $update = new UpdateEmployee($request, $id);
        $result = $update->executeProcess();

        $flashMessage = $result ? ['success' => 'Employee updated successfully.'] : ['error' => 'Sorry! Something went wrong.'];
        $redirectUrl = url('/employees') . '?page=' . $currentPage;

        return redirect($result ? $redirectUrl : back())->with($flashMessage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author Kyi Lin Lin Thant
     * @create 26/06/2023
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = $this->employeeInterface->getEmployeeById($id);
        
        if (!$employee) {
            $errors = new MessageBag(['error' => 'Sorry! Unable to delete this employee.']);
            return Redirect::back()->withErrors($errors);
        }
        
        $delete = new DeleteEmployee($id);
        $result = $delete->executeProcess();

        if ($result) {
            return redirect()->back()->with('success', 'Employee deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Sorry! Something went wrong.');
        }
    }
}