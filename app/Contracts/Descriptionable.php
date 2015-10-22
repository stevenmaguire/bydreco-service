<?php namespace App\Contracts;

interface Descriptionable
{
    /**
     * Retrieves validation rules for description creation.
     *
     * @return array
     */
    public function getValidationRules();
}
