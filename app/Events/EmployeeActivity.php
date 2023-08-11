<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;

class EmployeeActivity
{
    use Dispatchable, SerializesModels;

    public $action;
    public $employee;
    public $user;

    public function __construct($action, Employee $employee, $user)
    {
        $this->action = $action;
        $this->employee = $employee;
        $this->user = $user;
    }
}
