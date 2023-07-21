<?php

namespace App\Repositories;

use App\Models\Project;
use App\Interfaces\ProjectInterface;

/**
 * Manage database for Project.
 * @author Kyi Lin Lin Thant
 * @create 28/06/2023
 */
class ProjectRepository implements ProjectInterface
{
    /**
     * Get all projects from database.
     * @author Kyi Lin Lin Thant
     * @create 28/06/2023
     * @return array
     */
    public function getAllProjects() 
    {
        $projects = Project::all();
        return $projects;
    }

    /**
     * Get project by a specific ID from database.
     * @author Kyi Lin Lin Thant
     * @create 28/06/2023
     * @param  $id
     * @return array
     */
    public function getProjectById($id) 
    {
        $project = Project::find($id);
        return $project;
    }
}