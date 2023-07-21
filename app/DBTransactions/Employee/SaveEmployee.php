<?php

namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Classes\DBTransaction;
use App\Models\EmployeeProgrammingLanguage;
use App\Traits\ErrorTrait;
use Illuminate\Support\Facades\DB;

/**
 * Class SaveEmployee
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class SaveEmployee extends DBTransaction
{
    use ErrorTrait;

    private $request;

    /**
     * Assign Request
     * @author Kyi Lin Lin Thant
     * @param $request
     * @create 21/06/2023
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Save Employee
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function process()
    {
        try {
            DB::beginTransaction();

            // set request
            $request = $this->request;

            // handle image upload
            $imagePath = null; // default value if no image is uploaded

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/images'), $imageName);
                $imagePath = 'storage/images/' . $imageName;
            }

            // handle checkbox input for languages
            $languages = $request->input('languages', []);
            $languages = array_map('intval', $languages); // convert language IDs to integers
            $languages = array_filter($languages); // remove any empty values
            $languagesString = implode(',', $languages); // convert array to string

            // handle checkbox input for programming languages
            $programmingLanguages = $request->input('programming_languages', []);
            $programmingLanguages = array_map('intval', $programmingLanguages); // convert language IDs to integers
            $programmingLanguages = array_filter($programmingLanguages); // remove any empty values
            $programmingLanguagesString = implode(',', $programmingLanguages); // convert array to string

            // generate employee_id
            $employeeId = $this->generateEmployeeId();

            if (!$employeeId) {
                DB::rollBack();
                $this->error('Employee ID generation limit exceeded.', 500);
                return ['status' => false, 'error' => $this->getErrorMessage(), 'errorCode' => $this->getErrorCode()];
            }

            // create new Employee instance
            $employee = new Employee;

            // set employee data
            $employee->employee_id = $employeeId;
            $employee->name = $request->name;
            $employee->nrc = $request->nrc;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->gender = $request->gender;
            $employee->date_of_birth = $request->date_of_birth;
            $employee->address = $request->address;
            $employee->language = $languagesString;
            $employee->career_part = $request->career_part;
            $employee->level = $request->level;
            $employee->image = $imagePath;
            $employee->created_by = session('login_id');
            $employee->updated_by = session('login_id');

            // save employee
            $employee->save();

            if (!$employee) {
                DB::rollBack();
                $this->error('Failed to save employee!', 500);
                return ['status' => false, 'error' => $this->getErrorMessage(), 'errorCode' => $this->getErrorCode()];
            }

            // save programming languages for the employee
            foreach ($programmingLanguages as $programmingLanguageId) {
                $employeeProgrammingLanguage = new EmployeeProgrammingLanguage();
                $employeeProgrammingLanguage->employee_id = $employee->id;
                $employeeProgrammingLanguage->programming_language_id = $programmingLanguageId;
                $employeeProgrammingLanguage->created_by = session('login_id');
                $employeeProgrammingLanguage->updated_by = session('login_id');
                $employeeProgrammingLanguage->save();

                if (!$employeeProgrammingLanguage) {
                    DB::rollBack();
                    $this->error('Failed to save employee programming language!', 500);
                    return ['status' => false, 'error' => $this->getErrorMessage(), 'errorCode' => $this->getErrorCode()];
                }
            }

            DB::commit();

            return ['status' => true, 'error' => ''];
            
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'error' => $this->getErrorMessage()];
        }
    }

    /**
     * Generate Employee ID in custom format
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     */
    private function generateEmployeeId()
    {
        $employeeIds = [];

        // format id starting from 00001 to 99999
        for ($i = 1; $i <= 99999; $i++) {
            $employeeId = str_pad($i, 5, '0', STR_PAD_LEFT);
            $employeeIds[] = $employeeId;
        }

        // get the last saved employee_id from the database
        $lastEmployee = Employee::withTrashed()
        ->latest('employee_id')
        ->first();

        // increment the last employee_id to get the next available id
        $nextEmployeeId = $lastEmployee ? intval($lastEmployee->employee_id) + 1 : 1;

        // check if the generated id is within the valid range
        if ($nextEmployeeId <= count($employeeIds)) {
            return $employeeIds[$nextEmployeeId - 1];
        }

        return null;
    }
}