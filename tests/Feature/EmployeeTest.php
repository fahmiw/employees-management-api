<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Employee;
use App\Models\User;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function testRequiredFieldsForCreateEmployee()
    {   
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
 
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');

        $this->json('POST', 'api/employee', ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "statusCode" => 400,
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "job_title" => ["The job title field is required."],
                    "salary" => ["The salary field is required."],
                    "department" => ["The department field is required."],
                    "joined_date" => ["The joined date field is required."],
                ]
            ]);
    }

    public function testSuccessfulCreateEmployee()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
 
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');

        $employeeData = [
            "name" => "John Doe",
            "job_title" => "Manager",
            "salary" => 200000,
            "department" => "Sales",
            "joined_date" => "2022-03-01"
        ];

        $this->json('POST', 'api/employee', $employeeData, ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data" => [
                    "name",
                    "job_title",
                    "salary",
                    "department",
                    "joined_date",
                    "id"
                ]
            ]);
    }

    public function testTypeDataFieldsForUpdateEmployee()
    {   
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);

        $employee = Employee::factory()->create([
            "name" => "John Doe",
            "job_title" => "Manager",
            "salary" => 200000,
            "department" => "Sales",
            "joined_date" => "2022-03-01"
        ]);
 
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');

        $employeeData = [
            "name" => 2,
            "job_title" => 2,
            "salary" => "-",
            "department" => 2,
            "joined_date" => 2
        ];

        $this->json('PUT', 'api/employee/' . $employee->id, $employeeData,['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "statusCode" => 400,
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name must be a string."],
                    "job_title" => ["The job title must be a string."],
                    "salary" => ["The salary must be a number."],
                    "department" => ["The department must be a string."],
                    "joined_date" => ["The joined date is not a valid date."],
                ]
            ]);
    }

    public function testSuccessfulUpdateEmployee()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
        
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');
        
        $employee = Employee::factory()->create([
            "name" => "John Doe",
            "job_title" => "Manager",
            "salary" => 2000,
            "department" => "Sales",
            "joined_date" => "2022-03-01"
        ]);

        $employeeData = [
            "name" => "John Doe Edit",
            "job_title" => "Manager Edit",
            "salary" => 2001,
            "department" => "Sales Edit",
            "joined_date" => "2022-03-02"
        ];

        $this->json('PUT', 'api/employee/'. $employee->id, $employeeData, ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data" => [
                    "name",
                    "job_title",
                    "salary",
                    "department",
                    "joined_date"
                ]
            ]);
    }

    public function testSuccessfulGetAllEmployee()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
        
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');
        
        $employee = Employee::factory()->create([
            "name" => "John Doe",
            "job_title" => "Manager",
            "salary" => 2000,
            "department" => "Sales",
            "joined_date" => "2022-03-01"
        ]);

        
        $employee1 = Employee::factory()->create([
            "name" => "Walter White",
            "job_title" => "Manager",
            "salary" => 800000,
            "department" => "Scientist",
            "joined_date" => "2022-03-01"
        ]);

        $this->json('GET', 'api/employee/', ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data"
            ]);
    }

    public function testSuccessfulGetByIdEmployee()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
        
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');
        
        $employee = Employee::factory()->create([
            "name" => "John Doe",
            "job_title" => "Manager",
            "salary" => 2000,
            "department" => "Sales",
            "joined_date" => "2022-03-01"
        ]);

        $this->json('GET', 'api/employee/'. $employee->id, ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data"
            ]);
    }

    public function testSuccessfulDeleteEmployee()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);
        
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
        $user = $this->actingAs($user, 'api');
        
        $employee = Employee::factory()->create([
            "name" => "John Doe",
            "job_title" => "Manager",
            "salary" => 2000,
            "department" => "Sales",
            "joined_date" => "2022-03-01"
        ]);

        $this->json('DELETE', 'api/employee/'. $employee->id, [], ['token' => $token, 'Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "statusCode",
                "message",
                "data"
            ]);
    }
}
