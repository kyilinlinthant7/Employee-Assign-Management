<?php

namespace App\Interfaces;

interface ProjectAssignmentInterface
{
    // get project assignments
    public function getAllProjectAssignments();
    
    public function getProjectAssignmentById($id);

    // get employee name by id
    public function getEmployeeName($id);

    // get documentations
    public function getAllDocumentations();

    public function getDocumentationById($id);
}