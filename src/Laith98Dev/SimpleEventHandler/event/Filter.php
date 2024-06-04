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
use Laith98Dev\SimpleEventHandler\utils\CallbackHelper;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Event;
use pocketmine\item\Item;
use function count;

class Filter
{
	public const KEY_PLAYER_NAME = "player_name";
	public const KEY_MESSAGE = "message";
	public const KEY_BLOCK = "block";
	public const KEY_ITEM = "item";
	public const KEY_ENTITY = "entity";

	public static function fromClosure(string $key, Closure $rule) : self
	{
		return new self([$key => $rule]);
	}

	public static function fromPlayer(string $name) : self
	{
		return new self([
			self::KEY_PLAYER_NAME => CallbackHelper::createPlayer($name)
		]);
	}

	public static function fromMessage(string $message) : self
	{
		return new self([
			self::KEY_MESSAGE => CallbackHelper::createMessage($message)
		]);
	}

	public static function fromBlock(Block $block) : self
	{
		return new self([
			self::KEY_BLOCK => CallbackHelper::createBlock($block)
		]);
	}

	public static function fromItem(Item $item) : self
	{
		return new self([
			self::KEY_ITEM => CallbackHelper::createItem($item)
		]);
	}

	public static function fromEntity(Entity $entity) : self
	{
		return new self([
			self::KEY_ENTITY => CallbackHelper::createEntity($entity)
		]);
	}

	public function __construct(
		private array $rules = []
	){
		// NOOP
	}

	public function addRule(string $key, Closure $rule) : void
	{
		$this->rules[$key] = $rule;
	}

	public function getRules() : array
	{
		return $this->rules;
	}

	public function setPlayer(string $name) : self
	{
		if (isset($this->rules[self::KEY_PLAYER_NAME])){
			$this->rules[self::KEY_PLAYER_NAME] = CallbackHelper::createPlayer($name);
		}

		return $this;
	}

	public function setMessage(string $message) : self
	{
		if (isset($this->rules[self::KEY_MESSAGE])){
			$this->rules[self::KEY_MESSAGE] = CallbackHelper::createMessage($message);
		}

		return $this;
	}

	public function setBlock(Block $block) : self
	{
		if (isset($this->rules[self::KEY_BLOCK])){
			$this->rules[self::KEY_BLOCK] = CallbackHelper::createBlock($block);
		}

		return $this;
	}

	public function setItem(Item $item) : self
	{
		if (isset($this->rules[self::KEY_ITEM])){
			$this->rules[self::KEY_ITEM] = CallbackHelper::createItem($item);
		}

		return $this;
	}

	public function prepare(Event $event) : bool
	{
		$success = 0;

		foreach ($this->getRules() as $handler){
			if ($handler($event)){
				$success++;
			}
		}

		return $success === count($this->getRules());
	}
}
