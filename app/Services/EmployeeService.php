<?php
namespace App\Services;

use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Validator;
use Exception;

class EmployeeService 
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository) {
        $this->employeeRepository = $employeeRepository;
    }

    public function create($data) {
        try {
            $dataValidate = Validator::make($data->all(), [
                'name' => 'required|string',
                'job_title' => 'required|string',
                'salary' => 'required|number',
                'department' => 'required|string',
                'joined_date' => 'required|date'

            ]);

            if($dataValidate->fails()){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'The given data was invalid.',
                    'errors' => $dataValidate->errors()
                ], 400);
            }

            $employee = $this->employeeRepository->createEmployee([
                'name' => $data->name,
                'job_title' => $data->job_title,
                'salary' => $data->salary,
                'department' => $data->department,
                'joined_date' => $data->joined_date
            ]);

            return response()->json([
                'statusCode' => 201,
                'message' => 'Employee added successfully',
                'data' => $employee
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function update($id, $data) {
        try {
            $dataValidate = Validator::make($data->all(), [
                'name' => 'string',
                'job_title' => 'string',
                'salary' => 'number',
                'department' => 'string',
                'joined_date' => 'date'

            ]);

            if($dataValidate->fails()){
                return response()->json([
                    'statusCode' => 400,
                    'message' => 'The given data was invalid.',
                    'errors' => $dataValidate->errors()
                ], 400);
            }
            $updateData = [];

            if(isset($data->name)) {
                $updateData += ['name' => $data->name];
            }
            if(isset($data->job_title)) {
                $updateData += ['job_title' => $data->job_title];
            }
            if(isset($data->salary)) {
                $updateData += ['salary' => $data->salary];
            }
            if(isset($data->department)) {
                $updateData += ['department' => $data->department];
            }
            if(isset($data->joined_date)) {
                $updateData += ['joined_date' => $data->joined_date];
            }

            $employee = $this->employeeRepository->updateEmployee($id, $updateData);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Employee update successfully',
                'data' => $employee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getAll() {
        try {
            $dataEmployee = $this->employeeRepository->getEmployeeAll();

            return response()->json([
                "statusCode" => 200,
                "message" => "All Data Employee Showed",
                "data" => $dataEmployee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function getById($id) {
        try {
            $dataEmployee = $this->employeeRepository->getEmployeeById($id);

            return response()->json([
                "statusCode" => 200,
                "message" => `Data Employee id ${$id} Showed`,
                "data" => $dataEmployee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function delete($id) {
        try {
            $dataEmployee = $this->employeeRepository->deleteEmployee($id);

            return response()->json([
                "statusCode" => 200,
                "message" => `Data Employee id ${$id} deleted`,
                "data" => $dataEmployee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }
}