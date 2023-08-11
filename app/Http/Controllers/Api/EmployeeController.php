<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    public function add(Request $request) {
        return $this->employeeService->create($request);
    }

    public function edit(Request $request, $id) {   
        return $this->employeeService->update($id, $request);
    }
    
    public function showAll() {
        return $this->employeeService->getAll();
    }
    
    public function showById($id) {
        return $this->employeeService->getById($id);
    }
    
    public function destroy($id) {
        return $this->employeeService->delete($id);
    }
}
