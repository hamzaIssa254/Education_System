<?php

namespace App\Events;

use App\Models\Material;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseSessionUploadedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $material;
    /**
     * Create a new event instance.
     */
    public function __construct(Material $material)
    {
        $this->material = $material;
    }


}