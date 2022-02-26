<?php

namespace NewTheBuilder\Announce;

use NewTheBuilder\Announce\Command\AnnounceCommand;
use NewTheBuilder\Announce\Command\Buyannounce;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    private static Main $main;

    /**
     * @return void
     */
    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        self::$main = $this;
        if (!file_exists($this->getDataFolder() . "Config.yml")){
            $this->saveResource("Config.yml");
        }
        $this->getServer()->getCommandMap()->register("Announce", new AnnounceCommand());
        $this->getServer()->getCommandMap()->register("Announce", new Buyannounce());
    }

    public static function getInstance() : Main {
        return self::$main;
    }

}