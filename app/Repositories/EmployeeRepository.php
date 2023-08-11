<?php
namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function createEmployee($data) {
        return Employee::create($data);
    }

    public function updateEmployee($id, $data) {
        return Employee::where('id', '=', $id)
                        ->update($data);
    }

    public function getEmployeeAll() {
        return Employee::all();
    }

    public function getEmployeeById($id) {
        return Employee::find($id);
    }

    public function deleteEmployee($id) {
        return Employee::where('id', '=', $id)
                        ->delete();
    }


}