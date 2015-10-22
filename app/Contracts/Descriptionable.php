<?php namespace App\Contracts;

interface Descriptionable
{
    /**
     * Attempts to add vote to given description.
     *
     * @param integer  $descriptionId
     * @param boolean  $directionUp
     *
     * @return  Description
     */
    public function addDescriptionVote($descriptionId, $directionUp = true);

    /**
     * Retrieves validation rules for description creation.
     *
     * @return array
     */
    public function getValidationRules();
}
