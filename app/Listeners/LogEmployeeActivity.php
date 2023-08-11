<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\EmployeeActivity;
use App\Models\Log;

class LogEmployeeActivity
{
    public function handle(EmployeeActivity $event)
    {
        $action = ucfirst($event->action);

        Log::create([
            'activity' => $action,
            'entity_type' => 'employee',
            'entity_id' => $event->employee->id,
            'user_id' => $event->user->id,
        ]);
    }
}
