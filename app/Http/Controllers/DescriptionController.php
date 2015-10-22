<?php namespace App\Http\Controllers;

use App\Contracts\Descriptionable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DescriptionController extends Controller
{
    /**
     * Creates new controller instance.
     *
     * @param Productable  $product
     */
    public function __construct(Descriptionable $description)
    {
        $this->description = $description;
    }

    /**
     * Add a down vote to a given description.
     *
     * @return Response
     */
    public function voteDown($descriptionId, Request $request)
    {
        return $this->description->addDescriptionVote($descriptionId, false);
    }

    /**
     * Add an up vote to a given description.
     *
     * @return Response
     */
    public function voteUp($descriptionId, Request $request)
    {
        return $this->description->addDescriptionVote($descriptionId, true);
    }
}
