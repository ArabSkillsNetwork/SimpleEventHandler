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

use Closure;
use pocketmine\event\HandlerListManager;
use pocketmine\event\RegisteredListener;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class Handler
{
	private ?RegisteredListener $registeredListener = null;

	/** @var Bind[] */
	private array $bindings = [];

	private ?Filter $filter = null;

	private bool $isOnce = false;

	public function __construct(
		private Plugin $plugin,
		private string $eventClass,
		private Closure $callback,
		private int $priority,
		private bool $handleCancelled
	){
		$this->registeredListener = Server::getInstance()->getPluginManager()->registerEvent(
			$eventClass,
			function ($event){
				if($this->filter !== null){
					if(!$this->filter->prepare($event)){
						return;
					}
				}

				($this->getCallback())($event, $this);

				if($this->isOnce()){
					$this->kill();
				}
			},
			$priority,
			$plugin,
			$handleCancelled
		);
	}

	public function getPlugin() : Plugin
	{
		return $this->plugin;
	}

	public function getPriority() : int
	{
		return $this->priority;
	}

	public function isHandleCancelled() : bool
	{
		return $this->handleCancelled;
	}

	public function isRegistred() : bool
	{
		return $this->registeredListener !== null;
	}

	public function getListener() : ?RegisteredListener
	{
		return $this->registeredListener;
	}

	public function getCallback() : Closure
	{
		return $this->callback;
	}

	public function setFilter(?Filter $handler = null) : self
	{
		$this->filter = $handler;
		return $this;
	}

	public function getFilter() : ?Filter
	{
		return $this->filter;
	}

	public function setOnce(bool $val = true) : self
	{
		$this->isOnce = $val;
		return $this;
	}

	public function isOnce() : bool
	{
		return $this->isOnce;
	}

	public function bindWith(string $eventClass, ?int $priority = null, ?bool $handleCancelled = null) : self{
		if(!$this->isBindWith($eventClass)){
			$this->bindings[$eventClass] = new Bind(
				$this,
				$eventClass,
				$priority,
				$handleCancelled
			);
		}

		return $this;
	}

	public function isBindWith(string $eventClass)
	{
		return isset($this->bindings[$eventClass]);
	}

	public function unBindFrom(string $eventClass)
	{
		if($this->isBindWith($eventClass)){
			$bind = $this->bindings[$eventClass];
			$bind->getHandler()->kill();

			unset($this->bindings[$eventClass], $bind);
		}
	}

	public function kill() : void
	{
		if(!$this->isRegistred()){
			return;
		}

		HandlerListManager::global()->getListFor($this->eventClass)->unregister($this->getListener());
	}
}
