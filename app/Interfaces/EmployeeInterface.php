<?php

namespace App\Interfaces;

interface EmployeeInterface
{
    // get employees
    public function getAllEmployees();
    public function getEmployeeById($id);

    // get programming languages
    public function getAllProgrammingLanguages();

    // search cases by three inputs
    public function searchByEmployeeId($employeeId);
    public function searchByCareerPart($careerPart);
    public function searchByLevel($level);
    public function searchByCriteria($employeeId, $careerPart, $level);
    public function downloadFilteredData($employeeId, $careerPart, $level);

    // get distinct select box values for search inputs
    public function getDistinctCareerParts();
    public function getDistinctLevels();

    // get data from many tables for detail page
    public function detailShow($id);
}