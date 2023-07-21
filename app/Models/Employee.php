<?php

namespace App\Models;

use App\Models\ProgrammingLanguage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Employee Model
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class Employee extends Model
{
    protected $table = 'employees';
    protected $fillable = [
        'employee_id',
        'name',
        'nrc',
        'phone',
        'email',
        'gender',
        'date_of_birth',
        'address',
        'language',
        'career_part',
        'level',
        'created_by',
        'updated_by',
    ];
    use SoftDeletes;

    // relationship with employee and programming language 
    public function programmingLanguages()
    {
        return $this->belongsToMany(ProgrammingLanguage::class, 'employee_programming_language');
    }
}