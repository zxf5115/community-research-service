<?php
namespace App\Listeners\Api\Car;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Api\Module\Car;
use App\Events\Api\Car\InventoryEvent;

class InventoryListeners
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {

  }

  /**
   * Handle the event.
   *
   * @param  InventoryEvent  $event
   * @return void
   */
  public function handle(InventoryEvent $event)
  {
    try
    {
      $car_id = $event->car_id;
      $total  = $event->total;
      $type   = $event->type;

      $model = Car::getRow(['id' => $car_id]);

      if(1 == $type)
      {
        $model->increment('inventory_total', $total);
      }
      else
      {
        $model->decrement('inventory_total', $total);
      }

      return true;
    }
    catch(\Exception $e)
    {dd($e);
      return false;
    }
  }
}
