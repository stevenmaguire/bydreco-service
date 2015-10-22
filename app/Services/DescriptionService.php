<?php namespace App\Services;

use App\Contracts\Descriptionable;
use App\Description;
use App\Events;

class DescriptionService implements Descriptionable
{
    /**
     * Creates new service object.
     *
     * @param Description  $description
     */
    public function __construct(Description $description)
    {
        $this->description = $description;
    }

    /**
     * Attempts to add vote to given description.
     *
     * @param integer  $descriptionId
     * @param boolean  $directionUp
     *
     * @return  Description
     */
    public function addDescriptionVote($descriptionId, $directionUp = true)
    {
        $description = $this->description->findOrFail($descriptionId);

        if ($description->addVote($directionUp)->save()) {
            event(new Events\DescriptionWasUpdated($description));

            return $description;
        }

        throw new \Exception;
    }

    /**
     * Retrieves validation rules for description creation.
     *
     * @return array
     */
    public function getValidationRules()
    {
        return [
            'body' => ['required'],
        ];
    }
}
