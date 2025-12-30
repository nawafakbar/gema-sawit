<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SensorDataReceived implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $sensor_id;
    public $decibel;
    public $battery_percent;
    public $timestamp;

    public function __construct($sensor_id, $decibel, $battery_percent, $timestamp)
    {
        $this->sensor_id = $sensor_id;
        $this->decibel = $decibel;
        $this->battery_percent = $battery_percent;
        $this->timestamp = $timestamp;
    }

    public function broadcastOn()
    {
        return new Channel('sensors'); // channel publik bernama "sensors"
    }

    public function broadcastAs()
    {
        return 'SensorDataReceived';
    }
}
