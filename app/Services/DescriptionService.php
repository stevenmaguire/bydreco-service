<?php namespace App\Services;

use App\Contracts\Descriptionable;
use App\Description;

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
