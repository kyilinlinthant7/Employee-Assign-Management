<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SaveProjectAssignmentRequest
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class SaveProjectAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

     /**
     * Get the validation rules that apply to the request.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function rules()
    {
        $employeeId = $this->input('employee_id');
        $startDate = $this->input('start_date');
        $endDate = $this->input('end_date');
    
        $rules = [
            'employee_id' => [
                'required',
                'not_in:"0"',
            ],
            'project_name' => 'required|not_in:"0"',
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date', 'after:today'],
            'files' => 'required|array',
            'files.*' => 'required|file|max:10000',
        ];
    
        if ($startDate && $endDate) {
            $rules['employee_id'][] = Rule::unique('employee_project')->where(function ($query) use ($employeeId, $startDate, $endDate) {
                $query->where('employee_id', $employeeId)
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->where(function ($subQ) use ($startDate, $endDate) {
                            $subQ->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $startDate);
                        });
                    });
            });
        }
    
        return $rules;
    }    

    /**
     * Get the error messages for the defined validation rules.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function messages()
    {
        return [
            'employee_id.not_in' => 'Please choose an Employee ID.',
            'employee_id.unique' => 'The selected employee is already assigned to a project within the specified date range.',
            'project_name.required' => 'Project Name field is required.',
            'project_name.not_in' => 'Project Name field is required.',
            'start_date.required' => 'Start Date field is required.',
            'start_date.after_or_equal' => 'Start Date must be today or a future date.',
            'end_date.required' => 'End Date field is required.',
            'end_date.after' => 'End Date must be after Start Date.',
            'files.required' => 'Documentation field is required.',
            'files.*.required' => 'At least one file is required.',
            'files.*.max' => 'The files should not be exceeded more than 10 MB.',
        ];
    }
}