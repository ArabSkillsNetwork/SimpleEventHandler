<?php

namespace Laith98Dev\SimpleEventHandler\event;

use Laith98Dev\SimpleEventHandler\SimpleEventHandler;

class Bind
{
    private Handler $bindHandler;

    public function __construct(
        private Handler $handler,
        private string $eventClass,
        ?int $priority = null,
        ?bool $handleCancelled = null
    ){
        $priority ??= $handler->getPriority();
        $handleCancelled ??= $handler->isHandleCancelled();

        $this->bindHandler = SimpleEventHandler::createHandler($handler->getPlugin(), $eventClass, function ($event) use ($handler){
            if($handler->getFilter() !== null){
                if(!$handler->getFilter()->prepare($event)){
                    return;
                }
            }

            ($handler->getCallback())($event);
        }, $priority, $handleCancelled);
    }

    public function getHandler(): Handler
    {
        return $this->handler;
    }

    public function getBindHandler(): Handler
    {
        return $this->bindHandler;
    }
}