<?php
namespace App\Events\Api\Car;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InventoryEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $car_id = null;
  public $total  = null;
  public $type   = null;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($car_id, $total, $type = 1)
  {
    $this->car_id = $car_id;
    $this->total  = $total;
    $this->type   = $type;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new PrivateChannel('channel-name');
  }
}
