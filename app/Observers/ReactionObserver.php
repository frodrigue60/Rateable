<?php

namespace App\Observers;

use App\Models\Reaction;

class ReactionObserver
{
    /**
     * Handle the Reaction "created" event.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return void
     */
    public function created(Reaction $reaction)
    {
        $reaction->reactable->updateReactionCounters();
    }

    /**
     * Handle the Reaction "updated" event.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return void
     */
    public function updated(Reaction $reaction)
    {
        $reaction->reactable->updateReactionCounters();
    }

    /**
     * Handle the Reaction "deleted" event.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return void
     */
    public function deleted(Reaction $reaction)
    {
        $reaction->reactable->updateReactionCounters();
    }

    /**
     * Handle the Reaction "restored" event.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return void
     */
    public function restored(Reaction $reaction)
    {
        //
    }

    /**
     * Handle the Reaction "force deleted" event.
     *
     * @param  \App\Models\Reaction  $reaction
     * @return void
     */
    public function forceDeleted(Reaction $reaction)
    {
        //
    }
}
