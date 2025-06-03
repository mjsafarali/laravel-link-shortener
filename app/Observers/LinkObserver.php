<?php

namespace App\Observers;

use App\Models\Link;

class LinkObserver
{
    public function retrieved(Link $link)
    {
        $link->increment('visit_count');
//        $link->refresh();
    }

    /**
     * Handle the Link "created" event.
     */
    public function created(Link $link): void
    {
        //
    }

    /**
     * Handle the Link "updated" event.
     */
    public function updated(Link $link): void
    {
        //
    }

    /**
     * Handle the Link "deleted" event.
     */
    public function deleted(Link $link): void
    {
        //
    }

    /**
     * Handle the Link "restored" event.
     */
    public function restored(Link $link): void
    {
        //
    }

    /**
     * Handle the Link "force deleted" event.
     */
    public function forceDeleted(Link $link): void
    {
        //
    }
}
