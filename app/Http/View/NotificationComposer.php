<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Assignation;
use App\Models\Projet;

class NotificationComposer
{
    public function compose(View $view)
    {
        $today = now()->toDateString();

        $assignationsFinies = Assignation::whereDate('date_fin', '=', $today)
            ->whereHas('projet', function ($query) {
                $query->where('status', 'En cours');
            })
            ->get();

        $view->with('assignationsFiniesCount', $assignationsFinies->count());
    }
}
