<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Documentation;
use App\Models\EmployeeProject;
use App\Interfaces\ProjectAssignmentInterface;

/**
 * Manage database for EmployeeProject.
 * @author Kyi Lin Lin Thant
 * @create 30/06/2023
 */
class ProjectAssignmentRepository implements ProjectAssignmentInterface
{
    /**
     * Get all project assignments from database.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function getAllProjectAssignments() 
    {
        $employeeProjects = EmployeeProject::get();
        return $employeeProjects;
    }

    /**
     * Get project assignment by a specific ID from database.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  $id
     * @return array
     */
    public function getProjectAssignmentById($id) 
    {
        $employeeProject = EmployeeProject::find($id);
        return $employeeProject;
    }

    /**
     * Get employee name by a specific ID from database.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  $id
     * @return array
     */
    public function getEmployeeName($id)
    {
        return Employee::find($id);
    }

    /**
     * Get all documentations from database.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @return array
     */
    public function getAllDocumentations()
    {
        $documents = Documentation::get();
        return $documents;
    }

    /**
     * Get documentation by a specific ID from database.
     * @author Kyi Lin Lin Thant
     * @create 30/06/2023
     * @param  $id
     * @return array
     */
    public function getDocumentationById($id)
    {
        $document = Documentation::find($id);
        return $document;
    }
}