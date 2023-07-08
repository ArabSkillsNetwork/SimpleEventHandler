<?php

namespace Laith98Dev\SimpleEventHandler;

use Closure;
use Laith98Dev\SimpleEventHandler\event\Handler;
use pocketmine\event\EventPriority;
use pocketmine\plugin\Plugin;

class SimpleEventHandler {

    public static function createHandler(Plugin $plugin, string $eventClass, Closure $callback, int $priority = EventPriority::NORMAL, bool $handleCancelled = false): Handler
    {
        return new Handler($plugin, $eventClass, $callback, $priority, $handleCancelled);
    }
}