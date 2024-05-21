<?php

namespace App\Listeners;

use App\Events\UpdateViewCount;

class UpdateViewCountListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UpdateViewCount $event)
    {
        $model = $event->model;
        $model->view += 1;
        $model->save();
    }
}
