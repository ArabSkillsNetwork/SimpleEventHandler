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

namespace Laith98Dev\SimpleEventHandler\utils;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\utils\ColoredTrait;
use pocketmine\block\utils\DyeColor;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\entity\EntityEvent;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use function class_uses;
use function in_array;
use function method_exists;

class CallbackHelper
{
	public static function createPlayer(string $name) : Closure
	{
		return function (Event $event) use ($name){
			if($event instanceof PlayerEvent){
				if($event->getPlayer()->getName() === $name) return true;
			}

			return false;
		};
	}

	public static function createMessage(string $message) : Closure
	{
		return function (Event $event) use ($message){
			if($event instanceof PlayerChatEvent){
				if($event->getMessage() === $message) return true;
			}

			return false;
		};
	}

	public static function createBlock(Block $block) : Closure
	{
		return function (Event $event) use ($block){
			if($event instanceof BlockEvent){

				/**
				 * @var Block|ColoredTrait $block
				 * @var Block|ColoredTrait $eventBlock
				 */
				$eventBlock = $event->getBlock();

				$isColored = (in_array(ColoredTrait::class, class_uses($block::class), true) && !$block->getColor()->equals(DyeColor::WHITE())) && in_array(ColoredTrait::class, class_uses($eventBlock::class), true);

				if(
					$eventBlock->hasSameTypeId($block) &&
					($isColored ? $block->getColor()->equals($eventBlock->getColor()) : true)
				) return true;
			}

			return false;
		};
	}

	public static function createItem(Item $item) : Closure
	{
		return function (Event $event) use ($item){
			if(method_exists($event, "getItem")){

				/**
				 * @var PlayerItemUseEvent|Event $event
				 * @var Item $eventItem
				 */
				$eventItem = $event->getItem();

				if($item->equals($eventItem)) return true;
			}

			return false;
		};
	}

	public static function createEntity(Entity $entity) : Closure
	{
		return function (Event $event) use ($entity){
			if($event instanceof EntityEvent){
				if($event->getEntity() === $entity) return true;
			}

			return false;
		};
	}
}
