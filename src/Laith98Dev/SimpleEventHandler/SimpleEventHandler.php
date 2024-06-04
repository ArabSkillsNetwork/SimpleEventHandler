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

namespace Laith98Dev\SimpleEventHandler;

use Closure;
use Laith98Dev\SimpleEventHandler\event\Handler;
use pocketmine\event\EventPriority;
use pocketmine\plugin\Plugin;

class SimpleEventHandler {

	public static function createHandler(Plugin $plugin, string $eventClass, Closure $callback, int $priority = EventPriority::NORMAL, bool $handleCancelled = false) : Handler
	{
		return new Handler($plugin, $eventClass, $callback, $priority, $handleCancelled);
	}
}
