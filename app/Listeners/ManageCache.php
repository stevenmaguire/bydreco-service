<?php namespace App\Listeners;

use App\Contracts\Descriptionable;
use App\Contracts\Productable;
use App\Events;

class ManageCache
{
    /**
     * Creates the event listener.
     *
     * @return void
     */
    public function __construct(Descriptionable $description, Productable $product)
    {
        $this->description = $description;
        $this->product = $product;
    }

    /**
     * Handle description created events.
     */
    public function onDescriptionWasCreated($event)
    {
        $this->product->flushProductDescriptionCache($event->description->product);
    }

    /**
     * Handle product created events.
     */
    public function onProductWasCreated($event)
    {
        $this->product->flushProductCache($event->product);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     *
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen(
            Events\DescriptionWasCreated::class,
            get_class().'@onDescriptionWasCreated'
        );

        $events->listen(
            Events\ProductWasCreated::class,
            get_class().'@onProductWasCreated'
        );
    }
}
