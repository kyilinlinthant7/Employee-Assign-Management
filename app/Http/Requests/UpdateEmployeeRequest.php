<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateEmployeeRequest
 * @author Kyi Lin Lin Thant
 * @create 27/06/2023
 */
class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @author Kyi Lin Lin Thant
     * @create 27/06/2023
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @author Kyi Lin Lin Thant
     * @create 27/06/2023
     * @return array
     */
    public function rules()
    {
        $employeeId = $this->input('id');
        $limitedDob = Carbon::now()->subYears(18)->format('Y-m-d');
        
        return [
            'name' => [
                'required', 
                'regex:/^[a-zA-Z\s]+$/', 
                Rule::unique('employees')->where(function($query) use($employeeId) {
                    $query->whereNull('deleted_at');
                    if ($employeeId) {
                        $query->where('id', '!=', $employeeId);
                    }
                }),
            ],
            'nrc' => [
                'required', 
                'regex:/^\d{1,2}\/[A-Za-z]+\(N\)\d{6}$/i',
                Rule::unique('employees')->where(function($query) use($employeeId) {
                    $query->whereNull('deleted_at');
                    if ($employeeId) {
                        $query->where('id', '!=', $employeeId);
                    }
                }),
            ],
            'phone' => ['required', 'digits:11'],
            'email' => [
                'required', 
                'email',
                Rule::unique('employees')->where(function($query) use($employeeId) {
                    $query->whereNull('deleted_at');
                    if ($employeeId) {
                        $query->where('id', '!=', $employeeId);
                    }
                }),
            ],
            'gender' => 'required',
            'date_of_birth' => ['required', 'date', 'before_or_equal:' . $limitedDob],
            'address' => 'required',
            'languages' => 'required', 
            'programming_languages' => 'required', 
            'career_part' => 'required|not_in:"0"',
            'level' => 'required|not_in:"0"',
            'image' => 'file|mimes:jpg,jpeg,png',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @author Kyi Lin Lin Thant
     * @create 27/06/2023
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Employee Name field is required.',
            'name.regex' => 'Employee Name field is invalid.',
            'nrc.required' => 'NRC field is required.',
            'nrc.regex' => 'NRC format is invalid.',
            'phone.required' => 'Phone field is required.',
            'phone.digits' => 'Phone number must be 11 digits.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'date_of_birth.required' => 'Date of Birth field is required.',
            'date_of_birth.date' => 'Please enter a valid date format.',
            'date_of_birth.before_or_equal' => 'Employee must be at least 18 years old!',
            'address.required' => 'Address field is required.',
            'languages.required' => 'Languages field is required.',
            'programming_languages.required' => 'Programming Languages field is required.',
            'career_part.required' => 'Please select a Career Part.',
            'career_part.not_in' => 'Please select a Career Part.',
            'level.required' => 'Please select a Level.',
            'level.not_in' => 'Please select a Level.',
            'image.file' => 'Invalid image.',
            'image.mimes' => 'Wrong image format! Only .jpg, .jpeg, and .png formats are valid.',
        ];
    }
}