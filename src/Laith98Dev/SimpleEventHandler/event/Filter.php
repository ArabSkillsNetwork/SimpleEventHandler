<?php

namespace Laith98Dev\SimpleEventHandler\event;

use pocketmine\block\Block;
use pocketmine\block\utils\ColoredTrait;
use pocketmine\block\utils\DyeColor;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;

class Filter
{
    public const ALL_PLAYERS = "*";

    public static function fromPlayer(string $name = self::ALL_PLAYERS): self
    {
        return new self($name);
    }

    public static function fromMessage(string $message): self
    {
        return new self(self::ALL_PLAYERS, $message);
    }
    
    public static function fromBlock(Block $block): self
    {
        return new self(self::ALL_PLAYERS, null, $block);
    }
    
    public static function fromItem(Item $item): self
    {
        return new self(self::ALL_PLAYERS, null, null, $item);
    }

    public function __construct(
        private string $playerName = self::ALL_PLAYERS,
        private ?string $message = null,
        private ?Block $blockType = null,
        private ?Item $item = null,
        // TODO: add more
    ){
        
    }

    public function setPlayer(string $name): self
    {
        $this->playerName = $name;
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setBlock(Block $block): self
    {
        $this->blockType = $block;
        return $this;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;
        return $this;
    }

    public function prepare(Event $event): bool
    {
        $need = 0;
        // $need = count(array_filter([
        //     $this->playerName,
        //     $this->message,
        //     $this->blockType,
        // ], fn($item) => $item !== null));

        $count = 0;

        if($event instanceof PlayerEvent && ($name = $this->playerName) !== null){
            $need++;
            if($event->getPlayer()->getName() == $name) $count++;
        }
        
        if($event instanceof PlayerChatEvent && ($msg = $this->message) !== null){
            $need++;
            if($event->getMessage() == $msg) $count++;
        }
        
        if($event instanceof BlockEvent && ($block = $this->blockType) !== null){
            $need++;

            /**
             * @var Block|ColoredTrait $block
             * @var Block|ColoredTrait $eventBlock
             */
            $eventBlock = $event->getBlock();

            $isColored = (in_array(ColoredTrait::class, class_uses($block::class)) && !$block->getColor()->equals(DyeColor::WHITE())) && in_array(ColoredTrait::class, class_uses($eventBlock::class));

            if(
                $eventBlock->hasSameTypeId($this->blockType) &&
                ($isColored ? $block->getColor()->equals($eventBlock->getColor()) : true)
            ) $count++;
        }

        // insranceof PlayerItemEvent?
        if(($item = $this->item) !== null && method_exists($event, "getItem")){
            $need++;

            /**
             * @var PlayerItemUseEvent|Event $event
             * @var Item $eventItem
             */
            $eventItem = $event->getItem();

            if($item->equals($eventItem)) $count++;
        }
        
        return $count === $need;
    }
}