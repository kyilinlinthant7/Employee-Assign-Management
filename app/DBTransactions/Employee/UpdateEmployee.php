<?php
namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Traits\ErrorTrait;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\EmployeeProgrammingLanguage;

/**
 * Class UpdateEmployee
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class UpdateEmployee extends DBTransaction
{
    use ErrorTrait;
    
    private $request, $id;

    /**
     * Assign id
     * @author Kyi Lin Lin Thant
     * @param $request, $id
     * @create 30/06/2023
     */
    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }
    
    /**
     * Update Employee
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function process()
    {
        try {
            $request = $this->request;
            $id = $this->id;

            // find the employee by its ID
            $employee = Employee::find($id);

            if (!$employee) {
                return ['status' => false, 'error' => 'Employee not found.'];
            }

            // handle image update
            $currentImagePath = $employee->image;

            // create the 'images' directory if it doesn't exist
            $imagesDirectory = public_path('storage/images');
            if (!File::exists($imagesDirectory)) {
                File::makeDirectory($imagesDirectory, 0755, true);
            }

            // image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if ($image) {
                    // upload new image
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move($imagesDirectory, $imageName);
                    $imagePath = '/storage/images/' . $imageName;

                    // delete the old image if it exists
                    if ($currentImagePath) {
                        $oldImagePath = public_path($currentImagePath);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    // update the employee's image path
                    $employee->image = $imagePath;
                } else {
                    // image was removed
                    if ($currentImagePath) {
                        $oldImagePath = public_path($currentImagePath);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }
                    $employee->image = null;
                }
            } else {
                $newImagePath = $request->file('image');
                if ($newImagePath == '') {
                    $oldImagePath = public_path($currentImagePath);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    $imagePath = $newImagePath;
                } else {
                    $imagePath = null;
                }
            }

            // update the employee properties
            $employee->name = $request->name;
            $employee->nrc = $request->nrc;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->gender = $request->gender;
            $employee->date_of_birth = $request->date_of_birth;
            $employee->address = $request->address;
            $employee->career_part = $request->career_part;
            $employee->level = $request->level;
            $employee->image = $imagePath;
            $employee->updated_by = session('login_id');

            // handle checkbox input for languages
            $languages = $request->input('languages', []);
            $languages = array_map('intval', $languages); // convert language IDs to integers
            $languages = array_filter($languages); // remove any empty values
            $languagesString = implode(',', $languages); // convert array to string
            $employee->language = $languagesString;

            // get the old creater for pivot table - employee_programming_language (created_by value)
            $createdBy = $employee->created_by;

            // save the updated employee
            $employee->save();

            // if employee save fail
            if (!$employee) {
                return $this->error('Failed!', 500);
            }

            // get the selected programming language IDs
            $programmingLanguages = $request->input('programming_languages', []);
            $programmingLanguages = array_filter($programmingLanguages); // remove any empty values
            // remove the old programming language data for the employee
            $employee->programmingLanguages()->detach();

            // loop through the programming language IDs
            foreach ($programmingLanguages as $programmingLanguageId) {
                // create a new pivot model instance
                $employeeProgrammingLanguage = new EmployeeProgrammingLanguage;
                $employeeProgrammingLanguage->employee_id = $employee->id;
                $employeeProgrammingLanguage->programming_language_id = $programmingLanguageId;
                // to make remain the same creator after updated
                $employeeProgrammingLanguage->created_by = $createdBy;
                $employeeProgrammingLanguage->updated_by = session('login_id');
                $employeeProgrammingLanguage->save();
            }

            return ['status' => true, 'error' => '']; 
            
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'error' => 'An error occurred while updating the employee.'];
        }   
    }
}