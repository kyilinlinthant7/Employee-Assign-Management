<?php

namespace App\Interfaces;

interface ProjectInterface
{
    // get projects
    public function getAllProjects();

    public function getProjectById($id);
}