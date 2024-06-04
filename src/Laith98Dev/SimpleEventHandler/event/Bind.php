<?php

/*
 *
 *  _           _ _   _    ___   ___  _____
 * | |         (_) | | |  / _ \ / _ \|  __ \
 * | |     __ _ _| |_| |_| (_) | (_) | |  | | _____   __
 * | |    / _` | | __| '_ \__, |> _ <| |  | |/ _ \ \ / /
 * | |___| (_| | | |_| | | |/ /| (_) | |__| |  __/\ V /
 * |______\__,_|_|\__|_| |_/_/  \___/|_____/ \___| \_/
 *
 * Copyright (c) Laith98Dev
 *
 * Youtube: Laith Youtuber
 * Discord: Laith98Dev#0695 or @u.oo
 * Github: Laith98Dev
 * Email: spt.laithdev@gamil.com
 * Donate: https://paypal.me/Laith113
 *
 */

declare(strict_types=1);

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

		$this->bindHandler = SimpleEventHandler::createHandler($handler->getPlugin(), $eventClass, function ($event, $_) use ($handler){
			if($handler->getFilter() !== null){
				if(!$handler->getFilter()->prepare($event)){
					return;
				}
			}

			($handler->getCallback())($event, $_);
		}, $priority, $handleCancelled);
	}

	public function getHandler() : Handler
	{
		return $this->handler;
	}

	public function getBindHandler() : Handler
	{
		return $this->bindHandler;
	}
}
