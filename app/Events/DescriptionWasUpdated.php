<?php namespace App\Events;

use App\Description;

class DescriptionWasUpdated extends Event
{
    /**
     * Creates new event.
     *
     * @param Description  $description
     */
    public function __construct(Description $description)
    {
        $this->description = $description;
    }
}
