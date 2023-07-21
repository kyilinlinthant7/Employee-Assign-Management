<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Documentation Model
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class Documentation extends Model
{
    protected $table = 'documentations';
    protected $fillable = [
        'project_id',
        'file_name',
        'file_size',
        'file_path',
        'created_by',
        'updated_by',
    ];
    use SoftDeletes;
}