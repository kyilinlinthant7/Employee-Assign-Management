<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProgrammingLanguage Model
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class ProgrammingLanguage extends Model
{
    protected $table = 'programming_languages';
    protected $fillable = ['name'];
    use SoftDeletes;
}
