<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EmployeeProgrammingLanguage Model
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class EmployeeProgrammingLanguage extends Model
{
    protected $table = 'employee_programming_language';
    protected $fillable = [
        'employee_id',
        'programming_language_id',
        'created_by',
        'updated_by',
    ];
    use SoftDeletes;
}