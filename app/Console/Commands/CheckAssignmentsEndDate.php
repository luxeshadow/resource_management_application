<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assignation;
use App\Models\User;
use App\Notifications\AssignmentEndNotification;
use Carbon\Carbon;

class CheckAssignmentsEndDate extends Command
{
    protected $signature = 'check:assignments-end-date';
    protected $description = 'Vérifiez la date de fin des assignations et envoyez une notification si elles sont terminées';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();
        

        $assignations = Assignation::where('date_fin', '<=', $today)->get();

        $admin = User::where('is_admin', true)->first();

      

        return 0;
    }


}

