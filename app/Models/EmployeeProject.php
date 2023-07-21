<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EmployeeProject Model
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class EmployeeProject extends Model
{
    protected $table = 'employee_project';
    protected $fillable = [
        'employee_id',
        'project_id',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];
    use SoftDeletes;
}