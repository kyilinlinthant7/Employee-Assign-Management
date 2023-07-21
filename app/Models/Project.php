<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project Model
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];
    use SoftDeletes;
}