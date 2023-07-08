# SimpleEventHandler
Simple library to handle events for PocketMIne-MP

# Usage
- Create Handler:
```php
$handler = SimpleEventHandler::createHandler($this, PlayerChatEvent::class, function (PlayerChatEvent $event){
    $player = $event->getPlayer();
    // TODO
});
```

- Once:
This will make the event work once.
```php
$handler->once();
```
- Filter
You can filter the event by specific things like player name, block type, specific message, and specific item.
```php
use Laith98Dev\SimpleEventHandler\event\Filter;

// Filter::fromPlayer("Laith98Dev") // this will be for `PlayerEvent`
// Filter::fromMessage("Hello World") // this will be for `PlayerChatEvent`
// Filter::fromBlock(VanillaBlocks::DIRT()) // this will be for `BlockEvent`
// Filter::fromItem(VanillaItems::IRON_SWORD())

$handler->setFilter(Filter::fromPlayer("Laith98Dev"));
```
- Bindings
You can use the bindWith` function to bind many events together.
```php
$handler->bindWith($eventClass);
```
- Kill
This function will kill the handler.
```php
$handler->kill();
```

# Example
- Instead of doing this:
```php
class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onChat(PlayerChatEvent $event){
        if($event->getPlayer()->getName() == "Laith98Dev"){
            $this->doIt($event);
        }
    }
    
    public function onBlockBreak(BlockBreakEvent $event){
        if($event->getPlayer()->getName() == "Laith98Dev"){
            $this->doIt($event);
        }
    }

    public function doIt(PlayerChatEvent|BlockBreakEvent $event){
        $player = $event->getPlayer();

        echo "Hey, i'm working - " . $event::class . " - " . $player->getName() . "\n";
    }
}
```
- It can be done with:
```php
class Main extends PluginBase
{
    public function onEnable(): void
    {
        SimpleEventHandler::createHandler($this, PlayerChatEvent::class, function (PlayerChatEvent|BlockBreakEvent $event){
            $player = $event->getPlayer();
            echo "Hey, i'm working - " . $event::class . " - " . $player->getName() . "\n";
        })
        ->setFilter(Filter::fromPlayer("Laith98Dev")->setBlock(VanillaBlocks::DIRT()))
        ->bindWith(BlockBreakEvent::class);
    }
}
```
