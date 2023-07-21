<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SaveProjectRequest
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class SaveProjectRequest extends FormRequest
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
        $project = $this->route('project');

        return [
            'project_name_add' => [
                'required',
                Rule::unique('projects', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($project ? $project->id : null)
            ],
        ];
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
            'project_name_add.required' => 'Name field is required.',
            'project_name_add.unique' => 'Name has already been taken.',
        ];
    }
}